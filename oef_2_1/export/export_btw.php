<?php
require_once __DIR__ . "/../lib/autoload.php";
require_once "ExportBTW.php";

$export = new ExportCSV_BTW( $container->getDBManager(), $caps = true );
$export->Generate();