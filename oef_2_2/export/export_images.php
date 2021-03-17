<?php
require_once __DIR__ . "/../lib/autoload.php";
require_once "ExportImages.php";

$export = new ExportImages( $container->getDBManager(), $caps = true );
$export->Generate();