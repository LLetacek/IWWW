<div>
    <section id="hero">
        <h1 class="nadpis">Nabídka letadel</h1>
    </section>
</div>

<div style="text-align: center; margin: 2em auto 2em auto;">
    <form name="form" method="post">
        <select id="sel-kategorie" class="select" name="sel-kategorie" onchange="getRefresh(this.value)">
            <?php
            echo '<option disabled ';
            if(!isset($_COOKIE["selected-kategory"]))
                echo 'selected value';
            echo '> -- VYBER KATEGORII -- </option>';
            foreach (Kategory::getAllColumn("nazev") as $select) {
                echo '<option value="' . $select . '" ';
                if(isset($_COOKIE["selected-kategory"]) && $_COOKIE["selected-kategory"] == $select)
                    echo ' selected ';
                echo '>' . $select . '</option>';
            }
            ?>
        </select>
    </form>
</div>

<script>
    function getRefresh(selected) {
        document.cookie = "selected-kategory=" + selected + '; Path=/;';
        this.form.submit();
    }

</script>

<section>
    <div id="galerie" class="obrazekWrapper">
        <?php
        if(isset($_COOKIE["selected-kategory"])) {
            $data = Plane::getAllByKategory($_SESSION["uzivatel"], $_COOKIE["selected-kategory"]);
            foreach ($data as $key => $value) {
                echo "<a href=\"/index.php?page=detail&letadlo=".$value["imatrikulace"]."\" class=\"detail\">";
                echo "    <img src=\"" . $value["obrazek"] . "\" alt=\"" . $value["imatrikulace"] . "\">";
                echo "    <div class=\"prejeti\">";
                echo "        <div class=\"text\">" . $value["nazev"] . "</div>";
                echo "    </div>";
                echo "</a>";
            }
        }
        ?>
    </div>
</section>
