<section class="formWrapper">

        <?php
        $dataTable = new FormTable("LOGIN","Přihlásit");
        $dataTable->addColumn("userNameLogin", "Username", "text");
        $dataTable->addColumn("hesloLogin", "Heslo", "password");
        $dataTable->render("/index.php?page=main");
        ?>

</section>
