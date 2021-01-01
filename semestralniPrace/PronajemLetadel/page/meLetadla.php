<?php
    if(isset($_FILES["load"])) {
            $validation = Json::importJSON("load");
    }

    if($_POST) {
        if(isset($_POST["PŘIDEJ_LETADLO"])) {
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
            $data = Plane::getMyAll($_SESSION["uzivatel"]);
            $dataTableUpdate = new DataTable($data);
            foreach ($data as $row) {
                if(isset($_POST["editLet".$row["id_letadlo"]])) {
                    $validation = Plane::update($row["id_letadlo"],$_POST["letiste".$row["id_letadlo"]],
                        $_POST["stav".$row["id_letadlo"]],$_POST["cena_hodiny".$row["id_letadlo"]]);
                    $dataTableUpdate = new DataTable(Plane::getMyAll($_SESSION["uzivatel"]));
                    break;
                }
                else if(isset($_POST["zrusLet".$row["id_letadlo"]])) {
                    $validation = Plane::delete($row["id_letadlo"]);
                    $dataTableUpdate = new DataTable(Plane::getMyAll($_SESSION["uzivatel"]));
                    break;
                }
                else if(isset($_POST["rezeLet".$row["id_letadlo"]])) {
                    header("Location:  /index.php?page=rezervace&letadlo=".$row["imatrikulace"]);
                    exit();
                }
                else if(isset($_POST["zobrLet".$row["id_letadlo"]])) {
                    header("Location:  /index.php?page=detail&letadlo=".$row["imatrikulace"]);
                    exit();
                }
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

    function getOperace(value) {
        if(value === "pridej") {
            document.getElementById("letadla").innerHTML = '\
                <?php
                $dataTableNewAirplane = new FormTable("PŘIDEJ LETADLO","Přidat");
                $dataTableNewAirplane->addColumn("imatrikulace", "Imatrikulace", "text");
                $dataTableNewAirplane->addColumn("nazev", "Název", "text");
                $dataTableNewAirplane->addColumnSelect("letiste", "Letiště", Airport::getAirportColumn("icao"));
                $dataTableNewAirplane->addColumnSelect("stav", "Stav", Array("dostupny","nedostupny"));
                $dataTableNewAirplane->addColumnSelect("kategorie", "Kategorie", Kategory::getAllColumn("nazev"));
                $dataTableNewAirplane->addColumn("cena_hodiny", "Cena (Kč/hod)", "text");
                $dataTableNewAirplane->addColumn("obrazek", "Obrázek", "file");
                $dataTableNewAirplane->render("/index.php?page=meLetadla");
                ?>';
        }
        else if(value === "zobraz"){
            document.getElementById("letadla").innerHTML = '\
                <?php
                echo '\
<div id="buttonWrapper">\
    <form method="post" action="/page/download.php?user='.$_SESSION["uzivatel"].'">\
        <button onclick="saveJson()">Ulož JSON</button>\
    </form>\
    <br>\
    <form method="post" action="/index.php?page=meLetadla" enctype="multipart/form-data">\
        <label>Obnov z JSON: </label><input type="file" name="load" onchange="loadJson()">\
        <button type="submit" name="hiddenButton" id="hiddenButton" hidden="hidden"></button>\
    </form>\
</div>';
                if(!isset($dataTableUpdate))
                    $dataTableUpdate = new DataTable(Plane::getMyAll($_SESSION["uzivatel"]));
                $dataTableUpdate->addPictureColumn("obrazek", "Obrázek");
                $dataTableUpdate->addColumn("imatrikulace", "Imatrikulace");
                $dataTableUpdate->addColumn("kategorie", "Kategorie");
                $dataTableUpdate->addSelectionColumn("letiste", "Letiště", Airport::getAirportColumn("icao"));
                $dataTableUpdate->addEditableColumn("cena_hodiny", "Cena (Kč/hod)");
                $dataTableUpdate->addSelectionColumn("stav", "Přístupnost", Array("dostupny", "nedostupny"));
                $dataTableUpdate->renderWithButtons("id_letadlo","/index.php?page=meLetadla", $btn);
                ?>';
        }
        else if(value === "opravy") {
            document.getElementById("letadla").innerHTML = '\
                <?php
                $dataTableNewRepair = new FormTable("PŘIDEJ OPRAVU","Přidat");
                $dataTableNewRepair->addColumnSelect("letadlo", "Letadlo", Plane::getMyAllByColumn($_SESSION["uzivatel"],"imatrikulace"));
                $dataTableNewRepair->addColumn("duvod", "Důvod", "text");
                $dataTableNewRepair->addColumn("datum", "Datum", "date");
                $dataTableNewRepair->render("/index.php?page=meLetadla");
                ?>';
        }
    }
</script>

<section class="formWrapper">
    <div style="text-align: center; margin-bottom: 5px;">
        <form name="form" method="post">
            <select onchange="getOperace(this.value)" class="select">
                <?php
                $set = NULL;
                echo '<option disabled ';
                if(!isset($_POST["edit"]) && !isset($_POST["vloz"]) && !isset($_POST["opravy"]))
                    echo 'selected value';
                echo '> -- VYBER OPERACI -- </option>
                    <option value="zobraz" ';
                echo '>Správa letadel</option>';
                echo '<option value="pridej"';
                echo '>Přidat letadlo</option>';
                echo '<option value="opravy"';
                echo '>Přidat opravu</option>';
                ?>
            </select>
        </form>
    </div>

    <div id="letadla">
    </div>


    <div id="test">
    </div>
</section>


<?php
if($set != NULL)
    echo '<script>window.getOperace("'.$set.'"); </script>';
?>