<?php

namespace MusicBands\Services\ApiClients\ApiJsonMappers;

use DateTime;
use MusicBands\Exceptions\PublicException;
use MusicBands\Models\Album;
use MusicBands\Models\Image;
use MusicBands\Models\ImageDimension;
use MusicBands\Models\Token;
use MusicBands\Utils\Utils;
use stdClass;

class SpotifyJsonMapper extends ApiJsonMapper
{
    public function token(stdClass $responseObject): Token {
        $tokenType = $responseObject->token_type === 'Bearer' ? Token::TYPE_BEARER : null;
        $expiresAt = Utils::utcNow()->modify($responseObject->expires_in . ' second');

        $token = new Token($responseObject->access_token, $tokenType);
        $token->setExpiresAt($expiresAt);
        return $token;
    }

    public function album(stdClass $responseObject): Album{
        $album = new Album($responseObject->name);
        $album->setNumberOfTracks($responseObject->total_tracks);
        switch ($responseObject->release_date_precision){
            case 'year':
                $suffix = '-01-01 00:00:00';
                break;

            case 'month':
                $suffix = '-01 00:00:00';
                break;

            case 'day':
                $suffix = ' 00:00:00';
                break;

            default:
                throw PublicException::internalError(
                    sprintf('El release_date_precision (%s) no está soportado', $responseObject->release_date_precision),
                    'bad_release_date.spotify_json_mapper'
                );
        }
        $album->setReleasedAt(DateTime::createFromFormat('Y-m-d H:i:s', $responseObject->release_date . $suffix, Utils::utcTimeZone()));

        if(!empty($responseObject->images)){
            $imageData = $responseObject->images[0];
            $image = new Image($imageData->url);
            $image->setDimensions(new ImageDimension($imageData->height, $imageData->width));
            $album->setCover($image);
        }

        return $album;
    }
}