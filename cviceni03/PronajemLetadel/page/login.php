<?php
/*bud login nebo info o uzivateli*/
?>

<section class="formWrapper">

        <?php
        $dataTable = new FormTable("LOGIN","Přihlásit");
        $dataTable->addColumn("userNameLogin", "Username", "text");
        $dataTable->addColumn("hesloLogin", "Heslo", "password");
        $dataTable->render("/pronajemletadel/index.php?page=main");
        ?>

</section>
