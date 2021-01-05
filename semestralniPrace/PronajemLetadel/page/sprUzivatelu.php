<?php
    $data = User::getAll($_SESSION["uzivatel"]);

    $username = "username";
    $jmeno = "jmeno";
    $prijmeni = "prijmeni";
    $email = "email";
    $telefon = "telefon";

    $btn["Správa"]["updtUcet"] = "Oprav účet";
    $btn["Správa"]["zrusUcet"] = "Zrušit účet";

    if($_POST) {
        if(!empty(array_keys($_POST,"Oprav účet"))) {
            $id = explode('updtUcet',array_keys($_POST,"Oprav účet")[0])[1];
            $validation = User::update($id,
                                "",
                                "",
                                $_POST[$jmeno.$id],
                                $_POST[$prijmeni.$id],
                                $_POST[$email.$id],
                                $_POST[$telefon.$id]);
        }
        else if(!empty(array_keys($_POST,"Zrušit účet"))) {
            $id = explode('updtUcet',array_keys($_POST,"Zrušit účet")[0])[1];
            $validation = User::deleteUser($id);
        }
    }
?>
<section class="formWrapper">

    <?php
        $dataTable = new DataTable(User::getAll($_SESSION["uzivatel"]));
        $dataTable->addColumn($username, "Username");
        $dataTable->addEditableColumn($jmeno, "Jméno");
        $dataTable->addEditableColumn($prijmeni, "Příjmení");
        $dataTable->addEditableColumn($email, "Email");
        $dataTable->addEditableColumn($telefon, "Telefon");
        $dataTable->renderWithButtons("id_uzivatel","/index.php?page=sprUzivatelu", $btn);
    ?>

</section>