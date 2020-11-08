<?php
    $dataTable = User::getAll($_SESSION["uzivatel"]);

    $username = "username";
    $jmeno = "jmeno";
    $prijmeni = "prijmeni";
    $email = "email";
    $telefon = "telefon";

    foreach ($dataTable as $row) {
        if(isset($_POST["updtUcet".$row["id_uzivatel"]])) {
            $validation = User::update($row["id_uzivatel"],
                                $_POST[$username.$row["id_uzivatel"]],
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
        $dataTable->addColumn($jmeno, "Jméno");
        $dataTable->addColumn($prijmeni, "Příjmení");
        $dataTable->addColumn($email, "Email");
        $dataTable->addColumn($telefon, "Telefon");
        $dataTable->renderForManage("/pronajemletadel/index.php?page=sprava");
    ?>

</section>