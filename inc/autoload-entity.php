<?php

function autoloadEntity(string $class): void
{
    $entityName = $class;
    if (str_contains(haystack: $class, needle: "Manager")) {
        $entityName = str_replace("Manager", "", $class);
    }

    $entityFilePath = __DIR__."/".$entityName.".class.php";

    if (!file_exists($entityFilePath)) {
        return;
    }

    require_once $entityFilePath;
}
spl_autoload_register("autoloadEntity");
