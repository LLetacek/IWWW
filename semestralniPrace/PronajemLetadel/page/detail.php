
<section id="informace" class="infoWrapper">
<?php
    $letadlo = Plane::getPlaneImatrikulace($_GET["letadlo"]);
    if(!$_GET["letadlo"] || !isset($letadlo)) {
        header("Location:  /index.php?page=main");
        exit();
    }
    $letiste = Airport::getAirport($letadlo["letiste_id_letiste"]);
    $uzivatel = User::getUser($letadlo["majitel"]);
    $kategorie = Kategory::getKategory($letadlo["kategorie_id_kategorie"]);

    if($_POST && !empty($_POST["datum_rezervace"]) && !empty($_POST["cas_rezervace"]) && !empty($_POST["pocet_hodin"])) {
        $timestamp = $_POST["datum_rezervace"]." ".$_POST["cas_rezervace"].":00";
        $checktime = strtotime('-48 hours', strtotime($timestamp));
        $checktime = date("Y-m-d H:i:s",$checktime);
        if(date("Y-m-d H:i:s") < $checktime) {
            $validation = Plane::insertReservation($_SESSION["uzivatel"], $letadlo["id_letadlo"], $letadlo["cena_hodiny"], $timestamp, $_POST["pocet_hodin"]);
        } else {
            $validation["submit"]["nok"] = "Rezervaci je nutné provést alespoň 48 hodin předem.";
        }
    }
    else if($_POST) {
        $validation["submit"]["nok"] = "Nesplnuji se pozadavky pro rezervaci";
    }

    echo '<div class="detail-info">';
    echo '   <h1>'.$letadlo["imatrikulace"].'</h1>' .
         '   <hr>' .
         '   <p><b>Letadlo: </b>'.$letadlo["nazev"].'</p>' .
         '   <p><b>Letiště: </b>'.$letiste["icao"].' - ('.$letiste["poloha"].')</p>' .
         '   <p><b>Cena na hodinu: </b>'.$letadlo["cena_hodiny"].',–&nbsp;Kč</p>' .
         '   <p><b>Kategorie: </b>'.$kategorie["nazev"].'</p>' .
         '   <p><b>Stav: </b>'.$letadlo["stav"].'</p>' .
         '   <p><b>Kontakt na majitele: </b>'.
         '       <ul><li><i>Jméno: </i>'.$uzivatel["jmeno"].' '.$uzivatel["prijmeni"].'</li>' .
         '       <li><i>Email: </i>'.$uzivatel["email"].'</li>' .
         '       <li><i>Telefon: </i>'.$uzivatel["telefon"].'</li>' .
         '   </p>' ;
    echo '</div>';
    echo '<div class="detail-img">';
    echo '   <img src="'.$letadlo["obrazek"].'" alt="P47">';
    echo '   <div class="detail-opravy"><h2>Opravy</h2>';
    $dataTable = new DataTable(Plane::getRepair($letadlo["imatrikulace"]));
    $dataTable->addColumn("duvod","Důvod");
    $dataTable->addColumn("datum","Datum");
    $dataTable->render();
    echo '   </div>';
    echo '</div>';
    if($letadlo["stav"] == "dostupny") {
        echo '<div class="detail-form"><h2>Rezervace</h2>';
        $rezervaceTable = new FormTable("", "Rezervovat");
        $rezervaceTable->addColumn("datum_rezervace", "Datum rezervace", "date");
        $rezervaceTable->addColumn("cas_rezervace", "Čas rezervace", "time");
        $rezervaceTable->addColumn("pocet_hodin", "Počet hodin (1-12)", "number\" max=\"12\" min=\"1\"");
        $rezervaceTable->render("/index.php?page=detail&letadlo=" . $letadlo["imatrikulace"]);
        echo '</div>';
    }
?>
</section>

