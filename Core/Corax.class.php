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

    public function getDocumentById(array $ids):string {
        return $this->executeQuery($this->buildUrl($ids), Corax::GET);
    }

    public function getAllDocuments($startAt = null, $pageSize = null):string {
        $params = [];

        if($startAt !== null) {
            $params['startAt'] = $startAt;
        }

        if($pageSize !== null) {
            $params['pageSize'] = $pageSize;
        }

        return $this->executeQuery($this->buildUrl($params), Corax::GET);
    }

    public function putDocument(string $id, array $data):string {
        return $this->executeQuery($this->buildUrl(["documentId" => $id]), Corax::PUT, $data);
    }

    private function executeQuery(string $targetUrl, string $requestType, array $data = []):string {
        $curl = new Curl($targetUrl, $this->certPath, $this->certPass);
        $curl->setRequestType($requestType);
        $curl->setRequestData($data);
        return $curl->exec();
    }

    private function buildUrl(array $buildParams = []):string {
        $baseUrl = "{$this->server}/databases/{$this->database}/docs?";

        $queryString = '';
        foreach($buildParams as $key => $value) {
            /*
             * You're probably wondering "what the fuck is happening here?"
             * Well, RavenDB makes it possible to search for multiple IDs. And in the URL,
             * they are all called "id". Now, sadly, we can't have multiple keys with the same
             * name in an array. Therefore, I'm just checking if something is called
             * documentId and I replace that with "id" so the string looks correctly. That's also
             * the reason why I'm using "documentId" and not just "id" - that string can appear in
             * other params, so to avoid conflicts and wrong positives, I'm using documentId.
            */
            if(strpos($key, "documentId") >= 1) {
                $key = "id";
            }

            $queryString .= "{$key}={$value}&";
        }

        return $baseUrl . $queryString;
    }
}
