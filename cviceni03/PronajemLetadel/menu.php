<div id="menu">
    <a href="./index.php?page=main"><div id="logo"></div></a>

    <label for="hamburger">&#9776;</label>
    <input type="checkbox" id="hamburger"/>

    <nav>
        <a href="./index.php?page=nabidka">Nabídka</a>
        <?php
        if (isset($_SESSION["isLogged"]) && $_SESSION["isLogged"]) {
            echo '<a href="./index.php?page=profil">Můj profil</a>';

            $conn = Connection::getPdoInstance();
            $sql = "SELECT * FROM role WHERE nazev = \"admin\"";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            $idRole = 0;
            if(isset($result["id_role"])) {
                $idRole = $result["id_role"];
            }

            $user = User::getUser($_SESSION["uzivatel"]);
            if($user["role_id_role"] == $idRole) {
                echo '<a href="index.php?page=sprava">Uživatelé</a>';
                echo '<a href="./index.php?page=registrace">Vytvoř uživatele</a>';
            }
            echo '<a href="index.php?page=logout">Odhlásit</a>';
        } else {
            echo '<a href="./index.php?page=registrace">Registrace</a>';;
            echo '<a href="./index.php?page=login">Login</a>';
        }
        ?>
    </nav>
</div>