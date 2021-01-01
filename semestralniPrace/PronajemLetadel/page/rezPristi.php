<section class="formWrapper">
<?php
    $data = Plane::getReservationUserNext($_SESSION["uzivatel"]);

    foreach ($data as $key => $value) {
        $datumKey = str_replace(' ','_',$value["datum"]);
        if(isset($_POST["rem".$datumKey])){
            $conn = Connection::getPdoInstance();
            $stmt = $conn->prepare("
        DELETE FROM rezervace
        WHERE datum = :datum AND uzivatel_id_uzivatel = :uzivatel");
            $stmt->bindParam(':datum', $value["datum"]);
            $stmt->bindParam(':uzivatel', $_SESSION["uzivatel"]);
            $stmt->execute();
            break;
        }
    }

    $btn["Odhlášení"]["rem"] = "Odhlásit";
    $dataTableShow = new DataTable(Plane::getReservationUserNext($_SESSION["uzivatel"]));
    $dataTableShow->addColumn("imatrikulace", "Imatrikulace");
    $dataTableShow->addColumn("nazev", "Letadlo");
    $dataTableShow->addColumn("icao", "ICAO");
    $dataTableShow->addColumn("poloha", "Letiště");
    $dataTableShow->addColumn("datum", "Kdy");
    $dataTableShow->addColumn("pocet_hodin", "Doba (hod.)");
    $dataTableShow->addColumn("cena", "Cena");
    $dataTableShow->renderWithButtons("datum","/index.php?page=rezPristi",$btn);
?>
</section>
