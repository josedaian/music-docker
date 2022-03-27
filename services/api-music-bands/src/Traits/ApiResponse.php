<?php
namespace MusicBands\Traits;

use MusicBands\Utils\Response;
use Psr\Http\Message\ResponseInterface;

trait ApiResponse {
    public function errorResponse(ResponseInterface $response, $message = '', $code = 0, $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR): ResponseInterface {
        $responseData = [];
        $responseData['results'] = false;
        $responseData['infoCode'] = $code;
        $responseData['message'] = $message;

        return $response->withJson($responseData, $httpCode);
    }

    public function successResponse(ResponseInterface $response, $data = [], $httpCode = Response::HTTP_OK): ResponseInterface{
        $responseData = [];

        if(!empty($data)){
            $responseData = $data;
        }

        return $response->withJson($responseData, $httpCode);
    }
}
