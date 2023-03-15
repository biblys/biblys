<?php

function autoloadEntity($class)
{
    $Entity = biblysPath() . "/inc/" . $class . ".class.php";
    $EntityManager = biblysPath() . "/inc/" . str_replace("Manager", "", $class) . ".class.php";

    if (is_file($Entity)) {
        require_once $Entity;
    } elseif (is_file($EntityManager)) {
        require_once $EntityManager;
    }
}
spl_autoload_register("autoloadEntity");
