<?php
$dataTable = Cart::getCart($_SESSION["uzivatel"]);

if(isset($_POST["koupit"])) {
    Purchase::buy($_SESSION["uzivatel"]);
}

$produkt = "jmeno";
$cena = "cena";
$mnozstvi = "mnozstvi";

$btn["add"]["jmeno"] = "add";
$btn["rem"]["jmeno"] = "rem";
$btn["del"]["jmeno"] = "del";
$btn["add"]["hodnota"] = "+";
$btn["rem"]["hodnota"] = "−";
$btn["del"]["hodnota"] = "Odeber";

foreach ($dataTable as $row) {
    if(isset($_POST[$btn["add"]["jmeno"].$row["id_produkt"]])) {
        Cart::addItem($_SESSION["uzivatel"],$row["id_produkt"]);
        break;
    }
    if(isset($_POST[$btn["rem"]["jmeno"].$row["id_produkt"]])) {
        Cart::remItem($_SESSION["uzivatel"],$row["id_produkt"]);
        break;
    }
    if(isset($_POST[$btn["del"]["jmeno"].$row["id_produkt"]])) {
        Cart::delItem($_SESSION["uzivatel"],$row["id_produkt"]);
        break;
    }
}
?>
<section class="formWrapper">

    <?php
    $data = Cart::getCart($_SESSION["uzivatel"]);
    $dataTable = new DataTable($data);
    $dataTable->addColumn($produkt, "Položka");
    $dataTable->addColumn($cena, "Cena");
    $dataTable->addColumn($mnozstvi, "Množství");
    $dataTable->renderWithButtons("Úpravy","id_produkt","/Letacek_eshop/index.php?page=kosik", $btn);

    echo '<h2>Celková cena:</h2>';
    echo '<p>';
    $celkovaCena = 0;
    if(isset($data)) {
        foreach ($data as $row) {
            $celkovaCena = $celkovaCena + ($row["mnozstvi"]*$row["cena"]);
        }
    }

    echo $celkovaCena.',–&nbsp;Kč';
    echo '</p>';
    ?>
    <form action="/Letacek_eshop/index.php?page=kosik" method="post" style="text-align: center; padding-top: 15px;" >
        <input type="submit" name="koupit" value="Koupit" />
    </form>

</section>