<?php
    $dataTable = User::getAll($_SESSION["uzivatel"]);

    $username = "username";
    $jmeno = "jmeno";
    $prijmeni = "prijmeni";
    $email = "email";
    $telefon = "telefon";

    $btn["update"]["jmeno"] = "updtUcet";
    $btn["zrus"]["jmeno"] = "zrusUcet";
    $btn["update"]["hodnota"] = "Oprav účet";
    $btn["zrus"]["hodnota"] = "Zrušit účet";

    foreach ($dataTable as $row) {
        if(isset($_POST[$btn["update"]["jmeno"].$row["id_uzivatel"]])) {
            $validation = User::update($row["id_uzivatel"],
                                $_POST[$username.$row["id_uzivatel"]],
                                "",
                                $_POST[$jmeno.$row["id_uzivatel"]],
                                $_POST[$prijmeni.$row["id_uzivatel"]],
                                $_POST[$email.$row["id_uzivatel"]],
                                $_POST[$telefon.$row["id_uzivatel"]]);
            break;
        }
        if(isset($_POST[$btn["zrus"]["jmeno"].$row["id_uzivatel"]])) {
            $validation = User::deleteUser($row["id_uzivatel"]);
            break;
        }
    }
?>
<section class="formWrapper">

    <?php
        $dataTable = new DataTable(User::getAll($_SESSION["uzivatel"]));
        $dataTable->addEditableColumn($username, "Username");
        $dataTable->addEditableColumn($jmeno, "Jméno");
        $dataTable->addEditableColumn($prijmeni, "Příjmení");
        $dataTable->addEditableColumn($email, "Email");
        $dataTable->addEditableColumn($telefon, "Telefon");
        $dataTable->renderWithButtons("Správa","id_uzivatel","/Letacek_eshop/index.php?page=sprava", $btn);
    ?>

</section>