<?php

function autoloadEntity($class): void
{
    $Entity = __DIR__.$class.".class.php";
    $EntityManager = __DIR__."/".str_replace("Manager", "", $class).".class.php";

    if (is_file($Entity)) {
        require_once $Entity;
    } elseif (is_file($EntityManager)) {
        require_once $EntityManager;
    }
}
spl_autoload_register("autoloadEntity");
