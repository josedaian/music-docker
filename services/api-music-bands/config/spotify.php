<?php

return [
    'client_id' => $_ENV['SPOTIFY_CLIENT_ID'],
    'client_secret' => $_ENV['SPOTIFY_CLIENT_SECRET'],
    'timeout' => 30,
    'api_url' => $_ENV['SPOTIFY_API_URL'],
    'auth_url' => $_ENV['SPOTIFY_AUTH_URL'],
];