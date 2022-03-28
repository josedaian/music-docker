<?php
namespace MusicBands\Controllers;

use MusicBands\Exceptions\PublicException;
use MusicBands\Traits\ApiResponse;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Controller
{
    use ApiResponse;
    protected $container;

    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    protected function dispatchApiRequest(ResponseInterface $response, $callback ) {
        try {
            ignore_user_abort(true);    // Api should not stop because connection closed
            return call_user_func($callback, $response);

        } catch (\Throwable $unknown) {
            $publicException = PublicException::fromException( $unknown );
            // TODO agregar log
            return $this->errorResponse($response, $publicException->getText(), $publicException->getInfoCode(), $publicException->getHttpCode());
        }
    }
}