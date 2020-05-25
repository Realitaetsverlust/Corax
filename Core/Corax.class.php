<?php
namespace Realitaetsverlust\Corax\Core;

use Realitaetsverlust\Corax\Core\Exceptions\DuplicateIdException;
use Realitaetsverlust\Corax\Core\Exceptions\ParameterException;

require "Curl.php";

/**
 * Simple class to connect to RavenDB instance
 */
class Corax {
    public const PUT = "PUT";
    public const GET = "GET";
    public const POST = "POST";
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
     * @param string $server address to the server
     * @param string $database name of the database
     * @param string $certPath path to the certificate
     * @param string $certPass the password for the certfile (optional)
     */
    public function __construct(string $server, string $database, string $certPath = "", string $certPass = "") {
        $this->server = $server;
        $this->database = $database;
        $this->certPath = getcwd()."/".$certPath;
        $this->certPass = $certPass;
    }

    /**
     * Fetches one or more documents by ID
     *
     * @param array $ids
     * @return string
     */
    public function getDocumentById(array $ids):Response {
        $iteration = 0;
        $rekeyedIds = [];

        foreach($ids as $id) {
            $rekeyedIds["documentId{$iteration}"] = $id;
        }

        return $this->executeQuery($this->buildUrl($rekeyedIds), Corax::GET);
    }

    /**
     * Fetches all documents by prefix
     * @param string $prefix
     * @return string
     */
    public function getDocumentByPrefix(string $prefix):Response {
        return $this->executeQuery($this->buildUrl(['startsWith' => $prefix]), Corax::GET);
    }

    /**
     * checks if the given document exists
     *
     * @param string $id
     * @return bool
     */
    public function documentExists(string $id):bool {
        if(strlen($id) === 0) {
            try{
                throw new ParameterException('\'documentExists() parameter 1 cannot be empty\'');
            } catch(ParameterException $e) {
                echo $e->getMessage() . $e->getFile() . ' in line ' . $e->getLine();
                return false;
            }
        }

        if($this->getDocumentById([$id])->getStatusCode() === 404) {
            return false;
        }

        return true;
    }

    /**
     * Fetches every document in the database
     *
     * @param null $startAt how many docs to skip
     * @param null $pageSize amount of docs to return
     * @return string
     */
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

    public function putDocument(string $id, array $data, bool $checkExistence = true):bool {
        if($checkExistence === true && $this->documentExists($id) === true) {
            try {
                throw new DuplicateIdException("The document with the ID {$id} already exists. Please set the param '\$checkExistence' to false to overwrite the document.");
            } catch(DuplicateIdException $e) {
                echo "{$e->getMessage()}";
                return false;
            }
        }

        return $this->executeQuery($this->buildUrl(["documentId" => $id]), Corax::PUT, $data);
    }

    public function runQuery(string $query):string {
        return $this->executeQuery($this->buildQueryUrl(), Corax::POST, ['Query' => $query]);
    }

    private function executeQuery(string $targetUrl, string $requestType, array $data = []):Response {
        $curl = new Curl($targetUrl, $this->certPath, $this->certPass);
        $curl->setRequestType($requestType);
        $curl->setRequestData($data);
        return $curl->exec();
    }

    private function buildQueryUrl():string {
        return "{$this->server}/databases/{$this->database}/queries";
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
            if(strpos($key, "documentId") === 0) {
                $key = "id";
            }

            $queryString .= "{$key}={$value}&";
        }

        return $baseUrl . $queryString;
    }
}
