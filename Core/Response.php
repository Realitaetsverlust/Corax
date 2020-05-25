<?php

namespace Realitaetsverlust\Corax\Core;

use stdClass;

class Response {
    private int $statusCode;
    private ?stdClass $response;
    private string $responseRaw;

    public function __construct(int $statusCode, string $response) {
       $this->statusCode = $statusCode;
       $this->response = json_decode($response);
       $this->responseRaw = $response;
    }

    /**
     * @return int
     */
    public function getStatusCode():int {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getResponseBody():string {
        return $this->response;
    }
}