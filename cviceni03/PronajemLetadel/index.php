<?php
//https://github.com/petrfilip/KIT-IWWW/blob/4-php-classes/index.php

session_start();
$validation = [];

function __autoload($class) {
    require_once  './class/' . $class .'.php';
}

if (isset($_POST["hesloLogin"]) && isset($_POST["userNameLogin"])) {
    try {
        $conn = Connection::getPdoInstance();

        $sql = "SELECT * FROM uzivatel WHERE username = :userName";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':userName', $_POST["userNameLogin"]);

        $stmt->execute();
        $result = $stmt->fetch();

        if (isset($result["heslo"]) && md5($_POST["hesloLogin"]) == $result["heslo"]) {
            $_SESSION["uzivatel"] = $result["id_uzivatel"];
            $_SESSION["isLogged"] = true;
            $validation["submit"]["ok"] = "Uspesne prihlaseni - Vítej ".$result["jmeno"]."!";
        } else {
            $validation["submit"]["nok"] = "Spatne jmeno nebo heslo";
        }
    } catch (PDOException $e) {
        $validation["submit"]["nok"] = $e->getMessage();
    }
}

if ($_GET["page"] == "logout") {
    if(isset($_POST["zrusUcet"])) {
        User::deleteUser($_SESSION["uzivatel"]);
    }
    $_SESSION = [];
    session_destroy();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HANGAR</title>
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/fotogalerie.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<?php
  include "./menu.php";

  $pathToFile = "./page/" . $_GET["page"] . ".php";
  if (file_exists($pathToFile)) {
    include $pathToFile;
  } else {
    include "./page/main.php";
  }

  include "./footer.php";
?>

</body>
</html>

<?php
  include "alerty.php";
?>