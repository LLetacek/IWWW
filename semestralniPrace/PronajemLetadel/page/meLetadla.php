<?php
    if(isset($_FILES["load"])) {
            $validation = Json::importJSON("load");
    }

    if($_POST) {
        if(isset($_POST["operace"])) {
            header("Location: /index.php?page=meLetadla&sprava=".$_POST["operace"]);
        }
        else if(isset($_POST["PŘIDEJ_LETADLO"])) {
            if(!empty($_POST["imatrikulace"]) && !empty($_POST["nazev"]) && !empty($_POST["letiste"]) && !empty($_POST["stav"]) &&
            !empty($_POST["kategorie"]) && !empty($_POST["cena_hodiny"]) && is_numeric($_POST["cena_hodiny"])) {
                try {
                    $obrazek = UploadFile::upload("./img/", "obrazek");
                    if(empty($obrazek)) {
                        $validation["submit"]["nok"] = "Chyba - zkontrolujte zda obrázek není moc velký, doporučuje se také mít jméno obrázku shodnou s imatrikulací";
                    }
                    else {
                        $validation = Plane::insert($_SESSION["uzivatel"], $_POST["imatrikulace"], $_POST["nazev"],
                            $_POST["letiste"], $_POST["kategorie"], $obrazek, $_POST["stav"], $_POST["cena_hodiny"]);
                    }
                } catch (Exception $e) {
                    $validation["submit"]["nok"] = "Chyba - " . $e->getMessage();
                }
            } else {
                $validation["submit"]["nok"] = "Vyplňte správně všechny údaje!";
            }
        }
        else if (isset($_POST["PŘIDEJ_OPRAVU"])) {
            if(!empty($_POST["letadlo"]) && !empty($_POST["duvod"]) && !empty($_POST["datum"])) {
                if(strtotime($_POST["datum"]) <= strtotime('now')) {
                    $validation = Plane::insertRepair($_POST["letadlo"],$_POST["duvod"],$_POST["datum"]);
                }
                else {
                    $validation["submit"]["nok"] = "Nevkládejte opravy předčasně!";
                }
            } else {
                $validation["submit"]["nok"] = "Vyplňte všechny údaje!";
            }
        }
        else {
            if(!empty(array_keys($_POST,"Oprav údaj"))) {
                $id = explode('editLet',array_keys($_POST,"Oprav údaj")[0])[1];
                $validation = Plane::update($id,$_POST["letiste".$id],
                    $_POST["stav".$id],$_POST["cena_hodiny".$id]);
            }
            else if(!empty(array_keys($_POST,"Zruš"))) {
                $id = explode('zrusLet',array_keys($_POST,"Zruš")[0])[1];
                $validation = Plane::delete($id);
            }
            else if(!empty(array_keys($_POST,"Rezervace"))) {
                $id = explode('rezeLet',array_keys($_POST,"Rezervace")[0])[1];
                $plane = Plane::getPlane($id);
                header("Location:  /index.php?page=rezervace&rezervace[letadlo]=".$plane["imatrikulace"]);
                exit();
            }
            else if(!empty(array_keys($_POST,"Zobraz"))) {
                $id = explode('zobrLet',array_keys($_POST,"Zobraz")[0])[1];
                $plane = Plane::getPlane($id);
                header("Location:  /index.php?page=detail&letadlo=".$plane["imatrikulace"]);
                exit();
            }
        }
    }

    $btn["Správa"]["editLet"] = "Oprav údaj";
    $btn["Správa"]["zrusLet"] = "Zruš";
    $btn["Detail"]["rezeLet"] = "Rezervace";
    $btn["Detail"]["zobrLet"] = "Zobraz";

?>


<script>
    function saveJson() {
        this.form.submit();
    }

    function loadJson() {
        document.getElementById('hiddenButton').click();
    }

    function getOperace() {
        document.getElementById("hidden").click();
    }
</script>

<section class="formWrapper">
    <div style="text-align: center; margin-bottom: 5px;">
        <form name="sprava" method="post">
            <select onchange="getOperace()" class="select" name="operace">
                <?php
                echo '<option disabled ';
                if(!isset($_GET["sprava"]))
                    echo 'selected value';
                echo '> -- VYBER OPERACI -- </option>
                    <option value="zobraz" ';
                if(isset($_GET["sprava"]) && $_GET["sprava"] == "zobraz") {
                    echo 'selected';
                }
                echo '>Správa letadel</option>';
                echo '<option value="pridej"';
                if(isset($_GET["sprava"]) && $_GET["sprava"] == "pridej") {
                    echo 'selected';
                }
                echo '>Přidat letadlo</option>';
                echo '<option value="opravy"';
                if(isset($_GET["sprava"]) && $_GET["sprava"] == "opravy") {
                    echo 'selected';
                }
                echo '>Přidat opravu</option>';
                ?>
            </select>
            <button type="submit" name="hidden" id="hidden" hidden="hidden">
        </form>
    </div>

    <?php
    if(isset($_GET["sprava"])) {
        echo '<div id="letadla">';
        if ($_GET["sprava"] == "pridej") {
            $dataTableNewAirplane = new FormTable("PŘIDEJ LETADLO", "Přidat");
            $dataTableNewAirplane->addColumn("imatrikulace", "Imatrikulace", "text");
            $dataTableNewAirplane->addColumn("nazev", "Název", "text");
            $dataTableNewAirplane->addColumnSelect("letiste", "Letiště", Airport::getAirportColumn("icao"));
            $dataTableNewAirplane->addColumnSelect("stav", "Stav", array("dostupny", "nedostupny"));
            $dataTableNewAirplane->addColumnSelect("kategorie", "Kategorie", Kategory::getAllColumn("nazev"));
            $dataTableNewAirplane->addColumn("cena_hodiny", "Cena (Kč/hod)", "text");
            $dataTableNewAirplane->addColumn("obrazek", "Obrázek", "file");
            $dataTableNewAirplane->render("/index.php?page=meLetadla&sprava=pridej");
        } else if ($_GET["sprava"] == "zobraz") {
            echo '
        <div id="buttonWrapper">
            <form method="post" action="/page/download.php?user='.$_SESSION["uzivatel"].'">
                <button onclick="saveJson()">Ulož JSON</button>
            </form>
            <br>
            <form method="post" action="/index.php?page=meLetadla" enctype="multipart/form-data">
                <label>Obnov z JSON: </label><input type="file" name="load" onchange="loadJson()">
                <button type="submit" name="hiddenButton" id="hiddenButton" hidden="hidden"></button>
            </form>
        </div>';
            if (!isset($dataTableUpdate))
                $dataTableUpdate = new DataTable(Plane::getMyAll($_SESSION["uzivatel"]));
            $dataTableUpdate->addPictureColumn("obrazek", "Obrázek");
            $dataTableUpdate->addColumn("imatrikulace", "Imatrikulace");
            $dataTableUpdate->addColumn("kategorie", "Kategorie");
            $dataTableUpdate->addSelectionColumn("letiste", "Letiště", Airport::getAirportColumn("icao"));
            $dataTableUpdate->addEditableColumn("cena_hodiny", "Cena (Kč/hod)");
            $dataTableUpdate->addSelectionColumn("stav", "Přístupnost", array("dostupny", "nedostupny"));
            $dataTableUpdate->renderWithButtons("id_letadlo", "/index.php?page=meLetadla&sprava=zobraz", $btn);
        } else if ($_GET["sprava"] == "opravy") {
            $dataTableNewRepair = new FormTable("PŘIDEJ OPRAVU", "Přidat");
            $dataTableNewRepair->addColumnSelect("letadlo", "Letadlo", Plane::getMyAllByColumn($_SESSION["uzivatel"], "imatrikulace"));
            $dataTableNewRepair->addColumn("duvod", "Důvod", "text");
            $dataTableNewRepair->addColumn("datum", "Datum", "date");
            $dataTableNewRepair->render("/index.php?page=meLetadla&sprava=opravy");
        }
        echo '</div>';
    }
    ?>

</section>