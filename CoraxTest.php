<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

require "Core/Corax.class.php";

use Realitaetsverlust\Corax\Core\Corax;

$corax = new Corax(
    "https://a.realitaetsverlust.ravendb.community:8803",
    "testing",
    "certs/admin.client.certificate.realitaetsverlust.pem",
    "realitaetsverlust"
);

echo "<pre>";
var_dump($corax->getDocumentById("testdata/1"));
echo "</pre>";
