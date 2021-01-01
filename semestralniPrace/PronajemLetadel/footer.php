
<footer>
    <?php
    if (isset($_SESSION["uzivatel"])) {
    ?>
    <div id="paticka">
        <div>
            <span class="clanky">Nejpůjčovanější letadla</span>
            <?php
            $data = Plane::getFiveTop();
            foreach ($data as $key => $value) {
                echo '<li>'.
                    '<a href="/index.php?page=detail&letadlo='.$data[$key]["imatrikulace"].'">'.
                    $data[$key]["imatrikulace"].' ('.$data[$key]["kategorie"].')</a>'.
                    '</li>';
            }
            ?>
        </div>
        <div>
            <span class="clanky">Nově přidaná letadla</span>
            <?php
            $data = Plane::getFiveLast();
            foreach ($data as $key => $value) {
                echo '<li>'.
                    '<a href="/index.php?page=detail&letadlo='.$data[$key]["imatrikulace"].'">'.
                    $data[$key]["imatrikulace"].' ('.$data[$key]["kategorie"].')</a>'.
                    '</li>';
            }
            ?>
        </div>
    </div>
    <?php
    }
    ?>
    <div id="copyright">
        <p>Copyright © 2021</p>
    </div>
</footer>