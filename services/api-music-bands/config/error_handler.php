<?php
use MusicBands\Utils\Response;

return function ($app) {
    $container = $app->getContainer();
    $container['errorHandler'] = function () {
        return function ($request, $response){
            return Response::internalError($response);
        };
    };

    $container['notFoundHandler'] = function () {
        return function ($request, $response){
            return Response::notFound($response, 'La ruta solicitada no existe');
        };
    };

    $container['notAllowedHandler'] = function () {
        return function ($request, $response){
            return Response::notAllowed($response);
        };
    };

    $container['phpErrorHandler'] = function () {
        return function ($request, $response){
            return Response::internalError($response);
        };
    };
};