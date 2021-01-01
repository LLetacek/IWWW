<?php
if($_POST) {
    if(isset($_POST["PŘIDEJ_LETIŠTĚ"])) {
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
        $data = Airport::getAll();
        foreach ($data as $value) {
            if(isset($_POST["smaz".$value["id_letiste"]])) {
                $validation = Airport::delete($value["id_letiste"]);
                break;
            }
        }
    }
}
?>

<script>
    function getOperace(value) {
        if(value === "pridej") {
            document.getElementById("letiste").innerHTML = '\
                <?php
                $dataTableNewAirport = new FormTable("PŘIDEJ LETIŠTĚ","Přidat");
                $dataTableNewAirport->addColumn("icao", "ICAO", "text");
                $dataTableNewAirport->addColumn("poloha", "Poloha", "text");
                $dataTableNewAirport->render("/index.php?page=sprLetiste");
                ?>';
        } else if(value === "zobraz"){
            document.getElementById("letiste").innerHTML = '\
                <?php
                $btn["Správa"]["smaz"] = "Smazat";
                $dataTableShowAirport = new DataTable(Airport::getAll());
                $dataTableShowAirport->addColumn("icao", "ICAO");
                $dataTableShowAirport->addColumn("poloha", "Poloha");
                $dataTableShowAirport->renderWithButtons("id_letiste","/index.php?page=sprLetiste",$btn);
                ?>';
        }
    }
</script>

<section class="formWrapper">
    <div style="text-align: center; margin-bottom: 5px;">
        <form name="form" method="get">
            <select onchange="getOperace(this.value)" class="select">
                <?php
                $set = NULL;
                echo '<option disabled ';
                if(!isset($_POST["edit"]) && !isset($_POST["vloz"]))
                    echo 'selected value';
                echo '> -- VYBER OPERACI -- </option>
                    <option value="zobraz" ';
                if(isset($_POST["edit"])) {
                    echo 'selected';
                    $set = "zobraz";
                }
                echo '>Zobrazit letiště</option>
                    <option value="pridej"';
                if(isset($_POST["vloz"])) {
                    echo 'selected';
                    $set = "pridej";
                }
                echo '>Přidat letiště</option>';
                ?>
            </select>
        </form>
    </div>

    <div id="letiste">
    </div>

</section>


<?php
if($set != NULL)
    echo '<script>window.getOperace("'.$set.'"); </script>';
?>