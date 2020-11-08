<?php

if ($_POST) {
    if (empty($_POST["userName"]) || empty($_POST["heslo"]) || empty($_POST["jmeno"]) ||
        empty($_POST["prijmeni"]) || (empty($_POST["email"]) && empty($_POST["telefon"]))) {
        $validation["submit"]["nok"] = "Nesplnuji se pozadavky pro registraci";
    }

    if (empty($validation["submit"]["nok"])) {
        try {
            $conn = Connection::getPdoInstance();

            $userName = $_POST["userName"];
            $heslo = md5($_POST["heslo"]);
            $jmeno = $_POST["jmeno"];
            $prijmeni = $_POST["prijmeni"];
            $email = $_POST["email"];
            $telefon = $_POST["telefon"];

            $sql = "INSERT INTO uzivatel (username, heslo, email, telefon, jmeno, prijmeni, role_id_role) VALUES (:userName, :heslo, :email, :telefon, :jmeno, :prijmeni, 2)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':userName', $userName);
            $stmt->bindParam(':heslo', $heslo);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefon', $telefon);
            $stmt->bindParam(':jmeno', $jmeno);
            $stmt->bindParam(':prijmeni', $prijmeni);

            $stmt->execute();
            $validation["submit"]["ok"] = "Uspesna registrace - pokracujte na login";

        } catch (PDOException $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        } catch (Error $e) {
            $validation["submit"]["nok"] = $e->getMessage();
        }
    }
}
?>

<section class="formWrapper">

        <?php
        $dataTable = new FormTable("REGISTRACE","Registrovat");
        $dataTable->addColumn("userName", "Username", "text");
        $dataTable->addColumn("heslo", "Heslo", "password");
        $dataTable->addColumn("jmeno", "Jméno", "text");
        $dataTable->addColumn("prijmeni", "Příjmení", "text");
        $dataTable->addColumn("email", "Email", "email");
        $dataTable->addColumn("telefon", "Telefon", "text");
        $dataTable->render("/pronajemletadel/index.php?page=registrace");
        ?>

</section>

