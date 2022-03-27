<?php

namespace MusicBands\Models;

use DateTimeImmutable;
use DateTimeInterface;
use MusicBands\Exceptions\PublicException;
use MusicBands\Utils\Utils;

class Token extends Model
{
    CONST TYPE_BEARER = 100;
    private $value, $expiresAt, $type;
    private $validTypes = [self::TYPE_BEARER];

    public function __construct(string $value, int $type)
    {
        $this->ensureHasValidType($type);
        $this->type = $type;
        $this->value = $value;
    }

    public function getValue(): string {
        return $this->value;
    }

    public function getType(): int {
        return $this->type;
    }

    public function setExpiresAt(DateTimeInterface $expiresAt){
        $this->expiresAt = Utils::immutableUTCTime($expiresAt);
    }

    public function getExpiresAt(): ?DateTimeImmutable{
        return $this->expiresAt;
    }

    public function isExpired(): bool {
        return $this->getExpiresAt() < Utils::utcNow();
    }

    public function getTimeRemaining(): int {
        return $this->getExpiresAt()->getTimestamp() - Utils::utcNow()->getTimestamp();
    }

    private function ensureHasValidType(int $type){
        if(!in_array($type, $this->validTypes)){
            throw PublicException::validationError(sprintf('El tipo (%s) no es soportodao', $type), 'bad_type.token');
        }
    }
}