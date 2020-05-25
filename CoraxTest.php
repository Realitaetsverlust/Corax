<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

use Realitaetsverlust\Corax\Core\Corax;

$corax = new Corax('/var/www/html/RavenDBAdapter/corax.yml');

echo "<pre>";
var_dump($corax->documentExists('testdata/1'));
echo "</pre>";
