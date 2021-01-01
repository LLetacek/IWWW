<div id="menu">
<!-- https://github.com/petrfilip/KIT-IWWW/blob/4-php-classes/menu.php -->

    <label for="hamburger">&#9776;</label>
    <input type="checkbox" id="hamburger"/>
    <a href="./index.php?page=main"><div id="logo"></div></a>


    <nav>
        <?php
        /*https://www.w3schools.com/howto/howto_css_dropup.asp*/

        if (isset($_SESSION["isLogged"]) && $_SESSION["isLogged"]) {
            echo '<a href="./index.php?page=nabidka">Nabídka</a>';

            $conn = Connection::getPdoInstance();
            $sql = "
SELECT op.nazev 
FROM uzivatel uz
JOIN role r
ON uz.role_id_role = r.id_role
JOIN role_opravneni ro
ON ro.role_id_role = r.id_role
JOIN opravneni op
ON ro.opravneni_id_opravneni = op.id_opravneni 
WHERE uz.id_uzivatel = :uzivatel";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':uzivatel', $_SESSION["uzivatel"]);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

            $boolSprUziv = in_array("sprava letadel",$result);
            $boolSprLet = in_array("sprava uzivatelu",$result);
            $boolVytvLetiste = in_array("vytvor letiste",$result);
            if((bool) $boolSprUziv || (bool) $boolSprLet || (bool) $boolVytvLetiste) {
                echo '
                <div class="dropup">
                <button class="dropbtn">Správa</button>
                    <div class="dropup-content">';
                    if((bool) $boolSprUziv)
                        echo '<a href="./index.php?page=sprUzivatelu">Uživatelé</a>';
                    if((bool) $boolSprLet)
                        echo '<a href="./index.php?page=sprLetadel">Letadla</a>';
                    if((bool) $boolVytvLetiste)
                        echo '<a href="./index.php?page=sprLetiste">Letiště</a>';
                echo '
                    </div>
                </div>';
            }

            echo '
                <div class="dropup">
                <button class="dropbtn">Rezervace</button>
                    <div class="dropup-content">
                      <a href="./index.php?page=rezPristi">Příští</a>
                      <a href="./index.php?page=rezHistorie">Historie</a>
                    </div>
                </div>';
            echo '
                <div class="dropup">
                <button class="dropbtn"><div id="profil"></div></button>
                    <div class="dropup-content">
                      <a href="./index.php?page=profil">Profil</a>
                      <a href="./index.php?page=meLetadla">Letadla</a>
                      <a href="./index.php?page=logout">Odhlásit</a>
                    </div>
                </div>';
        } else {
            echo '<a href="./index.php?page=registrace">Registrace</a>';
            echo '<a href="./index.php?page=login">Login</a>';
        }
        ?>
    </nav>
</div>