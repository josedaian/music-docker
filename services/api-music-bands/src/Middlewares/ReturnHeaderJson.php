<?php

namespace MusicBands\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ReturnHeaderJson
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $response = $next($request, $response);
        return $response->withHeader('Content-Type','application/json')
            ->withHeader('Cache-Control', 'no-cache');
    }
}