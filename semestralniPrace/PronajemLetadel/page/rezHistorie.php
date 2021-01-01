<section class="formWrapper">
<?php
    $dataTableShow = new DataTable(Plane::getReservationUserHist($_SESSION["uzivatel"]));
    $dataTableShow->addColumn("imatrikulace", "Imatrikulace");
    $dataTableShow->addColumn("nazev", "Letadlo");
    $dataTableShow->addColumn("icao", "ICAO");
    $dataTableShow->addColumn("poloha", "Letiště");
    $dataTableShow->addColumn("datum", "Kdy");
    $dataTableShow->addColumn("pocet_hodin", "Doba (hod.)");
    $dataTableShow->addColumn("cena", "Cena");
    $dataTableShow->render();
?>
</section>
