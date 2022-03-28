<?php

namespace MusicBands\Controllers\v1;

use MusicBands\ApiModellers\v1\AlbumApiModeller;
use MusicBands\Controllers\Controller;
use MusicBands\Exceptions\PublicException;
use MusicBands\Models\Provider;
use MusicBands\Services\ApiClients\ApiFactories\MusicApiFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class GetAlbumController extends Controller
{
    public function __invoke(RequestInterface $request, ResponseInterface $response, array $args)
    {
        return $this->dispatchApiRequest($response, function ($response) use($request){
            $band = $request->getQueryParam('q', null);
            if(null === $band){
                throw PublicException::validationError('El campo `q` es requerido dentro de los parámetros del query', 'bad_request.get_albums');
            }

            return $this->successResponse($response, AlbumApiModeller::collection(
                MusicApiFactory::allocApiClient(Provider::SPOTIFY)->searchAlbums($band)
            ));
        });
    }
}