
<section class="formWrapper">

    <?php
    $dataTable = new DataTable(Purchase::getPurchase($_SESSION["uzivatel"]));
    $dataTable->addColumn("datum", "Datum nákupu");
    $dataTable->addColumn("jmeno", "Produkt");
    $dataTable->addColumn("cena", "Celková cena");
    $dataTable->render();
    ?>

</section>