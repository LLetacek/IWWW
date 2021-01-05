<section class="formWrapper">
<?php
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
        if (!empty(array_keys($_POST,"Zobraz"))) {
            $id = explode('detail',array_keys($_POST,"Zobraz")[0])[1];
            $plane = Plane::getPlane($id);
            header("Location:  /index.php?page=detail&letadlo=".$plane["imatrikulace"]);
            exit();
        }
        else if (!empty(array_keys($_POST,"Zruš"))) {
            $id = explode('zrusLet',array_keys($_POST,"Zruš")[0])[1];
            $validation = Plane::delete($id);
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
