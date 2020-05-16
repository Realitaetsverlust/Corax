<?php

namespace RavenDB;
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
     * URL to the chosen database
     * @var string
     */
    public string $baseUrl;

    /**
     * RavenDB constructor.
     *
     * @param $server string
     * @param $database string
     * @param $certPath string
     */
    public function __construct(string $server, string $database, string $certPath = "") {
        $this->server = $server;
        $this->database = $database;
        $this->certPath = $certPath;
        $this->baseUrl = $this->server . "/databases/" . $this->database;
    }

    public function testQuery() {
        $curl = curl_init($this->baseUrl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, '2');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, '1');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_SSLCERT, $this->certPath);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        echo "<pre>";
        var_dump($response);
        var_dump(curl_errno($curl));
        echo "</pre>";
        exit();

        curl_close($curl);

        return false;
    }
}
