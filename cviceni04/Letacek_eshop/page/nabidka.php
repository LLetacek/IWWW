<section>
    <?php
    //https://github.com/petrfilip/KIT-IWWW/tree/5-eshop

    try {
        $conn = Connection::getPdoInstance();
        $sql = "SELECT * FROM eshop.eshop_produkt";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $catalog = $result;
    } catch (PDOException $e) {
        print_r($e->getMessage());
    }

    if (isset($_GET["action"]) && $_GET["action"]["action"] == "add" && !empty($_GET["action"]["id"]) && isset($_SESSION["uzivatel"])) {
        Cart::addItem($_SESSION["uzivatel"],$_GET["action"]["id"]);
        header("Location: /Letacek_eshop/index.php?page=nabidka");
    }

    ?>

    <div class="infoWrapper">
        <h2>Nabídka</h2>
    </div>
    <div id="informace" class="infoWrapper">
        <?php

        foreach ($catalog as $item) {
            echo '
                <div class="catalog-item">
                <div class="catalog-img">
                ' . $item["obrazek"] . '
                </div>
                <h3>
                ' . $item["jmeno"] . '
                </h3>
                <div>
                ' . $item["cena"] . '
                </div>
                <a href="?page=nabidka&action[action]=add&action[id]=' . $item["id_produkt"] . '" class="catalog-buy-button">
                Buy
                </a>
                </div>';
        }

        ?>
    </div>
</section>
