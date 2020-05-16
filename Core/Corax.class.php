<?php
namespace Realitaetsverlust\Corax\Core;

require "Curl.php";

/**
 * Simple class to connect to RavenDB instance
 */
class Corax {
    public const PUT = "PUT";
    public const GET = "GET";
    public const DELETE = "DELETE";

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
     * UrlBuilder utility
     * @var string
     */
    public string $urlBuilder;

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
    }

    public function start(int $startAt) {
        $requestType = "GET";
        $command = "start={$startAt}";
    }

    public function pageSize(int $pageSize) {
        $requestType = "GET";
        $command = "pageSize={$pageSize}";
    }

    public function getDocumentById(string $id) {
        $requestType = "GET";
        $command = "id={$id}";
    }

    public function getAllDocuments($startAt, $pageSize) {
        return $this->executeQuery($this->buildUrl(["startAt" => $startAt, "pageSize" => $pageSize]), Corax::GET);
    }

    public function putDocument(string $id, array $data) {
        return $this->executeQuery($this->buildUrl(["id" => $id]), Corax::PUT, $data);
    }

    private function executeQuery(string $targetUrl, string $requestType, array $data = []) {
        $curl = new Curl($targetUrl, $this->certPath, $this->certPass);
        $curl->setRequestType($requestType);
        $curl->setRequestData($data);
        $response = $curl->exec();

        return $response;
    }

    public function buildUrl(array $buildParams = []) {
        $baseUrl = "{$this->server}/databases/{$this->database}/docs?";

        $queryString = '';
        foreach($buildParams as $key => $value) {
            $queryString .= "{$key}={$value}&";
        }

        return $baseUrl . $queryString;
    }
}
