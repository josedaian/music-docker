<?php

use MusicBands\Controllers\v1\GetAlbumController;
use MusicBands\Middlewares\ReturnHeaderJson;
use Slim\App;

return function ($app) {
    $app->group('/api', function (App $app){
        $app->group('/v1', function (App $app){
            #albums
            $app->group('/albums', function (App $app){
                $app->get('', GetAlbumController::class);
            });
        });
    })->add(ReturnHeaderJson::class);
};
