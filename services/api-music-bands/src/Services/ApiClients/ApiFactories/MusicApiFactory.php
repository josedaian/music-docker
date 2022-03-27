<?php

namespace MusicBands\Services\ApiClients\ApiFactories;

use MusicBands\Exceptions\PublicException;
use MusicBands\Models\Provider;
use MusicBands\Services\ApiClients\Contracts\MusicApiClient;
use MusicBands\Services\ApiClients\SpotifyApiClient;

final class MusicApiFactory
{
    /**
     * @param string $providerSlug
     * @return MusicApiClient
     * @throws PublicException
     */
    public static function allocApiClient(string $providerSlug): MusicApiClient {
        switch ($providerSlug){
            case Provider::SPOTIFY:
                return SpotifyApiClient::buildInstance();

            default:
                throw PublicException::validationError(sprintf('El proveedor(%s) no está soportado', $providerSlug), 'bad_slug.provider');
        }
    }
}