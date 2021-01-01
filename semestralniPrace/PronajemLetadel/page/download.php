<?php
function __autoload($class) {
    require_once  '../class/' . $class .'.php';
}

echo Json::exportJSON($_GET["user"]);
?>