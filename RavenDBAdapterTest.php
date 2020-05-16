<?php

require "RavenDBAdapter.class.php";

ini_set('display_errors', true);
error_reporting(E_ALL);

$raven = new RavenDB\RavenDB("https://a.realitaetsverlust.ravendb.community/", "testing", "certs/admin.client.certificate.realitaetsverlust.pfx");

echo "<pre>";
var_dump($raven->testQuery());
echo "</pre>";