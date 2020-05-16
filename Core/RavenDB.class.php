<?php
namespace Realitaetsverlust\RavenDBAdapter\Core;

require "Curl.php";

/**
 * Simple class to connect to RavenDB instance
 */
class RavenDB {
    /**
     * Server IP
     * @var string
     */
    public string $server;

    /**
     * Database name
     * @var string
     */
    public string $database;

    /**
     * Path to the certificate
     * @var string
     */
    public string $certPath;

    /**
     * Pass for certificate
     * @var string
     */
    public string $certPass;

    /**
     * URL to the chosen database
     * @var string
     */
    public string $baseUrl;

    /**
     * RavenDB constructor.
     *
     * @param string $server
     * @param string $database
     * @param string $certPath
     * @param string $certPass
     */
    public function __construct(string $server, string $database, string $certPath = "", string $certPass = "") {
        $this->server = $server;
        $this->database = $database;
        $this->certPath = getcwd()."/".$certPath;
        $this->certPass = $certPass;
        $this->baseUrl = $this->server . "/databases";
    }

    public function testQuery() {
        $curl = new Curl($this->baseUrl, $this->certPath, $this->certPass, "GET");
        $response = $curl->exec();
        $curl->close();

        echo($response);
        exit();

        curl_close($curl);

        return false;
    }
}
