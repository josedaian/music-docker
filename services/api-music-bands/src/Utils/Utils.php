<?php

namespace MusicBands\Utils;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use MusicBands\Exceptions\PublicException;

class Utils
{
    private static $utcTimeZone;

    static function utcNow(): DateTimeImmutable {
        return new DateTimeImmutable('now', self::utcTimeZone());
    }

    static function utcTimeZone(): DateTimeZone {
        if(!self::$utcTimeZone){
            self::$utcTimeZone = new DateTimeZone('UTC');
        }
        return self::$utcTimeZone;
    }

    static function immutableUTCTime(?\DateTimeInterface $time): ?DateTimeImmutable{
        if($time){
            self::utcTimeOrFail($time);
            if($time instanceof DateTimeImmutable){
                return $time;
            }
            return DateTimeImmutable::createFromMutable($time);
        }
        return null;
    }

    static function utcTimeOrFail(?DateTimeInterface $time):void{
        if(null !== $time && $time->getOffset() !== 0){
            throw PublicException::validationError('La fecha/hora no es UTC', 'bad_datetime.utc', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}