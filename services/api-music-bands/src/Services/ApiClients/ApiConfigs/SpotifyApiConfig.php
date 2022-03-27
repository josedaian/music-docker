<?php

namespace MusicBands\Services\ApiClients\ApiConfigs;

class SpotifyApiConfig extends ApiConfig
{
    CONST AUTH_URL = 'auth_url';
    CONST CLIENT_ID = 'client_id';
    CONST CLIENT_SECRET = 'client_secret';

    static function buildConfig(): ApiConfig {
        $spotifyConfigs = require __DIR__ . '/../../../../config/spotify.php';
        $props = [];
        foreach( $spotifyConfigs  as $property => $value){
            $props[$property] = $value;
        }
        return new SpotifyApiConfig($props);
    }
}