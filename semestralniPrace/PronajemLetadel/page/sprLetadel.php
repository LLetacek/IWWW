<section class="formWrapper">
<?php

    $data = Plane::getAll($_SESSION["uzivatel"]);

    $btn["Detail"]["detail"] = "Zobraz";
    $btn["Správa"]["zrusLet"] = "Zruš";

    $imatrikulace = "imatrikulace";
    $kategorie = "kategorie";
    $obrazek = "obrazek";
    $majitel = "majitel";
    $cena = "cena_hodiny";
    $stav = "stav";
    $letiste = "letiste";

    if($_POST) {
        foreach($data as $row) {
            if (isset($_POST["detail" . $row["id_letadlo"]])) {
                header("Location:  /index.php?page=detail&letadlo=".$row["imatrikulace"]);
                exit();
            }
            else if (isset($_POST["zrusLet" . $row["id_letadlo"]])) {
                $validation = Plane::delete($row["id_letadlo"]);
                break;
            }
        }
    }

    $data = Plane::getAll($_SESSION["uzivatel"]);
    $dataTable = new DataTable($data);
    $dataTable->addPictureColumn($obrazek, "Obrázek");
    $dataTable->addColumn($imatrikulace, "Imatrikulace");
    $dataTable->addColumn($kategorie, "Kategorie");
    $dataTable->addColumn($majitel, "Majitel");
    $dataTable->addColumn($letiste, "Letiště");
    $dataTable->addColumn($cena, "Cena (Kč/hod)");
    $dataTable->addColumn($stav, "Přístupnost");
    $dataTable->renderWithButtons("id_letadlo","/index.php?page=sprLetadel", $btn);
?>
</section>
