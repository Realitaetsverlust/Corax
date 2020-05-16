<?php

use Realitaetsverlust\RavenDBAdapter\Core\RavenDB;

require "RavenDBAdapter.class.php";

ini_set('display_errors', true);
error_reporting(E_ALL);

$raven = new RavenDB("https://a.realitaetsverlust.ravendb.community:8803", "testing", "certs/admin.client.certificate.realitaetsverlust.pem", "realitaetsverlust");

echo "<pre>";
var_dump($raven->testQuery());
echo "</pre>";