<?php
if ($_POST) {
    $validation = User::update(
                    $_SESSION["uzivatel"],
                    $_POST["userName"],
                    $_POST["heslo"],
                    $_POST["jmeno"],
                    $_POST["prijmeni"],
                    $_POST["email"],
                    $_POST["telefon"]);
}
?>

<section class="formWrapper">

    <?php
        if(isset($_SESSION["uzivatel"])) {
            $user = User::getUser($_SESSION["uzivatel"]);
            $dataTable = new FormTable("PROFIL", "upravit");
            $dataTable->addColumnValue("userName", "Username", "text", $user["username"]);
            $dataTable->addColumn("heslo", "Heslo", "password");
            $dataTable->addColumnValue("jmeno", "Jméno", "text", $user["jmeno"]);
            $dataTable->addColumnValue("prijmeni", "Příjmení", "text", $user["prijmeni"]);
            $dataTable->addColumnValue("email", "Email", "email",$user["email"]);
            $dataTable->addColumnValue("telefon", "Telefon", "text",$user["telefon"]);
            $dataTable->render("/index.php?page=profil");
        }
    ?>

        <form action="/index.php?page=logout" method="post" style="text-align: center; padding-top: 15px;" >
            <input type="submit" name="zrusUcet" value="Zrušit účet" />
        </form>

</section>