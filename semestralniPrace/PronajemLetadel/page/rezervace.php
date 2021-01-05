<?php
    if($_POST) {
        if(isset($_POST["operace"])) {
            header("Location: /index.php?page=rezervace&rezervace[letadlo]=".$_GET["rezervace"]["letadlo"]."&rezervace[typ]=".$_POST["operace"]);
        }

        if(!empty(array_keys($_POST,"Odhlásit"))){
            $datum = explode('rem',array_keys($_POST,"Odhlásit")[0])[1];
            $datumKey = str_replace('_',' ',$datum);
            $conn = Connection::getPdoInstance();
            $letadlo = Plane::getPlaneImatrikulace($_GET["rezervace"]["letadlo"]);
            $stmt = $conn->prepare("
    DELETE FROM rezervace
    WHERE datum = :datum AND letadlo_id_letadlo = :letadlo");
            $stmt->bindParam(':datum', $datumKey);
            $stmt->bindParam(':letadlo', $letadlo["id_letadlo"]);
            $stmt->execute();
        }
    }
?>

<script>
    function getOperace() {
        document.getElementById("hidden").click();
    }
</script>

<section class="formWrapper">
    <?php
    if(isset($_GET["rezervace"]["letadlo"])) {
        $letadlo = Plane::getPlaneImatrikulace($_GET["rezervace"]["letadlo"]);
        if($letadlo["majitel"]!=$_SESSION["uzivatel"]) {
            header("Location:  /index.php?page=main");
            exit();
        }
    }
    else {
        header("Location:  /index.php?page=main");
        exit();
    }

    echo '<div style="text-align: center;"><h1>REZERVACE PRO ['.$_GET["rezervace"]["letadlo"].']</h1></div>';
?>

    <div style="text-align: center; margin-bottom: 5px;">
        <form name="rezervace" method="post">
            <select onchange="getOperace()" class="select" name="operace">
                <?php
                $set = NULL;
                echo '<option disabled ';
                if(!isset($_GET["rezervace"]["typ"]))
                    echo 'selected value';
                echo '> -- REZERVACE -- </option>';
                echo '<option value="nadchazejici" ';
                if(isset($_GET["rezervace"]["typ"]) && $_GET["rezervace"]["typ"] == "nadchazejici")
                    echo 'selected';
                echo '>Nadcházející</option>';
                echo '<option value="probehle"';
                if(isset($_GET["rezervace"]["typ"]) && $_GET["rezervace"]["typ"] == "probehle")
                    echo 'selected';
                echo '>Proběhlé</option>';
                ?>
            </select>
            <button type="submit" name="hidden" id="hidden" hidden="hidden">
        </form>
    </div>

    <div id="rezervace">
        <?php
        if(isset($_GET["rezervace"]["typ"]) && $_GET["rezervace"]["typ"] == "nadchazejici") {
            $btn["Odhlášení"]["rem"] = "Odhlásit";
            $dataTableShow = new DataTable(Plane::getReservationPlaneNext($_GET["rezervace"]["letadlo"]));
            $dataTableShow->addColumn("jmeno", "Jméno");
            $dataTableShow->addColumn("prijmeni", "Prijmení");
            $dataTableShow->addColumn("telefon", "Telefon");
            $dataTableShow->addColumn("email", "Email");
            $dataTableShow->addColumn("datum", "Kdy");
            $dataTableShow->addColumn("pocet_hodin", "Doba (hod.)");
            $dataTableShow->addColumn("cena", "Cena");
            $dataTableShow->renderWithButtons("datum","/index.php?page=rezervace&rezervace[letadlo]=".$_GET["rezervace"]["letadlo"]."&rezervace[typ]=nadchazejici",$btn);
        } else if(isset($_GET["rezervace"]["typ"]) && $_GET["rezervace"]["typ"] == "probehle") {
            $dataTableShow = new DataTable(Plane::getReservationPlaneHist($_GET["rezervace"]["letadlo"]));
            $dataTableShow->addColumn("jmeno", "Jméno");
            $dataTableShow->addColumn("prijmeni", "Prijmení");
            $dataTableShow->addColumn("telefon", "Telefon");
            $dataTableShow->addColumn("email", "Email");
            $dataTableShow->addColumn("datum", "Kdy");
            $dataTableShow->addColumn("pocet_hodin", "Doba (hod.)");
            $dataTableShow->addColumn("cena", "Cena");
            $dataTableShow->render();
        }

        if(isset($_GET["rezervace"]["typ"])) {
            echo '<div id="buttonWrapper"><button id="printButton" onclick="window.print();">Vytisknout</button></div>';
        }
        ?>
    </div>
</section>
