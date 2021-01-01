<?php
    $dataTable = User::getAll($_SESSION["uzivatel"]);

    $username = "username";
    $jmeno = "jmeno";
    $prijmeni = "prijmeni";
    $email = "email";
    $telefon = "telefon";

    $btn["Správa"]["updtUcet"] = "Oprav účet";
    $btn["Správa"]["zrusUcet"] = "Zrušit účet";

    foreach ($dataTable as $row) {
        if(isset($_POST["updtUcet".$row["id_uzivatel"]])) {
            $validation = User::update($row["id_uzivatel"],
                                "",
                                "",
                                $_POST[$jmeno.$row["id_uzivatel"]],
                                $_POST[$prijmeni.$row["id_uzivatel"]],
                                $_POST[$email.$row["id_uzivatel"]],
                                $_POST[$telefon.$row["id_uzivatel"]]);
            break;
        }
        if(isset($_POST["zrusUcet".$row["id_uzivatel"]])) {
            $validation = User::deleteUser($row["id_uzivatel"]);
            break;
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