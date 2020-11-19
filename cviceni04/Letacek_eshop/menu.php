<div id="menu">

    <a href="./index.php?page=main"><div id="logo"></div></a>
    <label for="hamburger">&#9776;</label>
    <input type="checkbox" id="hamburger"/>

    <nav>
        <?php
        /*https://www.w3schools.com/howto/howto_css_dropup.asp*/

        if (isset($_SESSION["isLogged"]) && $_SESSION["isLogged"]) {
            echo '<a href="./index.php?page=nabidka">Nabídka</a>';

            $conn = Connection::getPdoInstance();
            $sql = "SELECT * FROM eshop.eshop_role WHERE nazev = \"admin\"";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            $idRole = 0;
            if(isset($result["id_role"])) {
                $idRole = $result["id_role"];
            }

            $user = User::getUser($_SESSION["uzivatel"]);
            if($user["role_id_role"] == $idRole) {
                echo '
                <div class="dropup">
                <button class="dropbtn">Uživatelé</button>
                    <div class="dropup-content">
                      <a href="./index.php?page=sprava">Spravuj</a>
                      <a href="./index.php?page=registrace">Vytvoř</a>
                    </div>
                </div>';
            }
            echo '
                <div class="dropup">
                <button class="dropbtn">Nákupy</button>
                    <div class="dropup-content">
                      <a href="./index.php?page=kosik">Košík</a>
                      <a href="./index.php?page=obednavky">Obědnávky</a>
                    </div>
                </div>';
            echo '<a href="./index.php?page=profil">Profil</a>';
            echo '<a href="./index.php?page=logout">Odhlásit</a>';
        } else {
            echo '<a href="./index.php?page=registrace">Registrace</a>';;
            echo '<a href="./index.php?page=login">Login</a>';
        }
        ?>
    </nav>
</div>