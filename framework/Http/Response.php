<?php

namespace Laralite\Framework\Http;

class Response
{
    public function __construct(
        public string $content = "",
        public int    $status = 200,
        public array  $headers = [],
    )
    {

    }

    public function send(): void
    {
        //$this->getCode($this->status);
        http_response_code($this->status);
        echo $this->content;
    }

//    public function getCode($code): void
//    {
//        switch ($code) {
//            // Informational Codes
//            case 100:
//                http_response_code(100);
//                break;
//            case 101:
//                http_response_code(101);
//                break;
//
//            // Successful Codes
//            case 200:
//                http_response_code(200);
//                break;
//            case 201:
//                http_response_code(201);
//                break;
//            case 204:
//                http_response_code(204);
//                break;
//
//            // Client Error Codes
//            case 400:
//                http_response_code(400);
//                break;
//            case 401:
//                http_response_code(401);
//                break;
//            case 403:
//                http_response_code(403);
//                break;
//            case 404:
//                http_response_code(404);
//                break;
//
//            // Server Error Codes
//            case 500:
//                http_response_code(500);
//                break;
//            case 503:
//                http_response_code(503);
//                break;
//
//            default:
//                http_response_code($this->status);
//                break;
//        }
//    }
}