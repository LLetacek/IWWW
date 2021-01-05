<?php
if($_POST) {
    if(isset($_POST["operace"])) {
        header("Location: /index.php?page=sprLetiste&sprava=".$_POST["operace"]);
    }
    else if(isset($_POST["PŘIDEJ_LETIŠTĚ"])) {
        try {
            $conn = Connection::getPdoInstance();

            $icao = $_POST["icao"];
            $poloha = $_POST["poloha"];

            unset($_POST["icao"]);
            unset($_POST["poloha"]);

            if ((isset($icao) && $icao != "") && (isset($poloha) && $poloha != "")) {
                $sql = "INSERT INTO letiste (icao, poloha) VALUES (:icao, :poloha)";
                $stmt = $conn->prepare($sql);

                $stmt->bindParam(':icao', $icao);
                $stmt->bindParam(':poloha', $poloha);

                $stmt->execute();
                $validation["submit"]["ok"] = "Uspesne pridani letiste";
            } else {
                $validation["submit"]["nok"] = "ICAO ani poloha nesmi byt prazdna.";
            }
        } catch (PDOException $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        } catch (Error $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        }
    }
    else {
        if(!empty(array_keys($_POST,"Smazat"))) {
            $id = explode('smaz',array_keys($_POST,"Smazat")[0])[1];
            $validation = Airport::delete($id);
        }
    }
}
?>

<script>
    function getOperace() {
        document.getElementById("hidden").click();
    }
</script>

<section class="formWrapper">
    <div style="text-align: center; margin-bottom: 5px;">
        <form name="sprava" method="post">
            <select name="operace" onchange="getOperace()" class="select">
                <?php
                echo '<option disabled ';
                if(!isset($_GET["sprava"]))
                    echo 'selected value';
                echo '> -- VYBER OPERACI -- </option>
                    <option value="zobraz" ';
                if(isset($_GET["sprava"]) && $_GET["sprava"] == "zobraz") {
                    echo 'selected';
                }
                echo '>Zobrazit letiště</option>
                    <option value="pridej"';
                if(isset($_GET["sprava"]) && $_GET["sprava"] == "pridej") {
                    echo 'selected';
                }
                echo '>Přidat letiště</option>';
                ?>
            </select>
            <button type="submit" name="hidden" id="hidden" hidden="hidden">
        </form>
    </div>

    <div id="letiste">
        <?php
        if (isset($_GET["sprava"])) {
            if ($_GET["sprava"] == "pridej") {
                $dataTableNewAirport = new FormTable("PŘIDEJ LETIŠTĚ","Přidat");
                $dataTableNewAirport->addColumn("icao", "ICAO", "text");
                $dataTableNewAirport->addColumn("poloha", "Poloha", "text");
                $dataTableNewAirport->render("/index.php?page=sprLetiste&sprava=pridej");
            }
            else {
                $btn["Správa"]["smaz"] = "Smazat";
                $dataTableShowAirport = new DataTable(Airport::getAll());
                $dataTableShowAirport->addColumn("icao", "ICAO");
                $dataTableShowAirport->addColumn("poloha", "Poloha");
                $dataTableShowAirport->renderWithButtons("id_letiste","/index.php?page=sprLetiste&sprava=zobraz",$btn);
            }
        }
        ?>
    </div>

</section>