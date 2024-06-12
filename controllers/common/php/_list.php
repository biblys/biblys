<?php

if ($site->getOpt('virtual_stock')) {
    require '_list_virtual_stock.php';
} else {
    require_once '_list_bookshop.php';
}
