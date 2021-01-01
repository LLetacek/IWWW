<?php
    $data = Plane::getReservationPlaneNext($_GET["letadlo"]);

    foreach ($data as $key => $value) {
        $datumKey = str_replace(' ','_',$value["datum"]);
        if(isset($_POST["rem".$datumKey])){
            $conn = Connection::getPdoInstance();
            $letadlo = Plane::getPlaneImatrikulace($_GET["letadlo"]);
            $stmt = $conn->prepare("
    DELETE FROM rezervace
    WHERE datum = :datum AND letadlo_id_letadlo = :letadlo");
            $stmt->bindParam(':datum', $value["datum"]);
            $stmt->bindParam(':letadlo', $letadlo["id_letadlo"]);
            $stmt->execute();
            break;
        }
    }
?>

<script>
    function getOperace(value) {
        if(value === "nadchazejici") {
            document.getElementById("rezervace").innerHTML = '\
                <?php
                $btn["Odhlášení"]["rem"] = "Odhlásit";
                $dataTableShow = new DataTable(Plane::getReservationPlaneNext($_GET["letadlo"]));
                $dataTableShow->addColumn("jmeno", "Jméno");
                $dataTableShow->addColumn("prijmeni", "Prijmení");
                $dataTableShow->addColumn("telefon", "Telefon");
                $dataTableShow->addColumn("email", "Email");
                $dataTableShow->addColumn("datum", "Kdy");
                $dataTableShow->addColumn("pocet_hodin", "Doba (hod.)");
                $dataTableShow->addColumn("cena", "Cena");
                $dataTableShow->renderWithButtons("datum","/index.php?page=rezervace&letadlo=".$_GET["letadlo"],$btn);
                echo '<div id="buttonWrapper"><button id="printButton" onclick="window.print();">Vytisknout</button></div>';
                ?>';
        } else if(value === "probehle"){
            document.getElementById("rezervace").innerHTML = '\
                <?php
                $dataTableShow = new DataTable(Plane::getReservationPlaneHist($_GET["letadlo"]));
                $dataTableShow->addColumn("jmeno", "Jméno");
                $dataTableShow->addColumn("prijmeni", "Prijmení");
                $dataTableShow->addColumn("telefon", "Telefon");
                $dataTableShow->addColumn("email", "Email");
                $dataTableShow->addColumn("datum", "Kdy");
                $dataTableShow->addColumn("pocet_hodin", "Doba (hod.)");
                $dataTableShow->addColumn("cena", "Cena");
                $dataTableShow->render();
                echo '<div id="buttonWrapper"><button id="printButton" onclick="window.print();">Vytisknout</button></div>';
                ?>';
        }
    }
</script>

<section class="formWrapper">
    <?php
    if($_GET["letadlo"]) {
        $letadlo = Plane::getPlaneImatrikulace($_GET["letadlo"]);
        if($letadlo["majitel"]<>$_SESSION["uzivatel"]) {
            header("Location:  /index.php?page=main");
            exit();
        }
    }
    else {
        header("Location:  /index.php?page=main");
        exit();
    }

    echo '<div style="text-align: center;"><h1>REZERVACE PRO ['.$_GET["letadlo"].']</h1></div>';
?>

    <div style="text-align: center; margin-bottom: 5px;">
        <form name="form" method="post">
            <select onchange="getOperace(this.value)" class="select">
                <?php
                $set = NULL;
                echo '<option disabled ';
                if(!isset($_POST["edit"]) && !isset($_POST["vloz"]))
                    echo 'selected value';
                echo '> -- REZERVACE -- </option>';
                echo '<option value="nadchazejici" ';
                echo '>Nadcházející</option>';
                echo '<option value="probehle"';
                echo '>Proběhlé</option>';
                ?>
            </select>
        </form>
    </div>

    <div id="rezervace">
    </div>
</section>

<?php
if($set != NULL)
    echo '<script>window.getOperace("'.$set.'"); </script>';
?>
