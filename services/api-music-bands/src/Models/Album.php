<?php

namespace MusicBands\Models;

use DateTimeImmutable;
use DateTimeInterface;
use MusicBands\Utils\Utils;

class Album extends Model
{
    private $name, $releasedAt, $numberOfTracks, $cover;

    public function __construct(string $name){
        $this->name = $name;
    }

    public function getName(): string{
        return $this->name;
    }

    public function setReleasedAt(DateTimeInterface $releasedAt): void{
        $this->releasedAt = Utils::immutableUTCTime($releasedAt);
    }

    public function getReleasedAt(): ?DateTimeImmutable{
        return $this->releasedAt;
    }

    public function setNumberOfTracks(int $numberOfTracks): void{
        $this->numberOfTracks = $numberOfTracks;
    }

    public function getNumberOfTracks(): ?int{
        return $this->numberOfTracks;
    }

    public function setCover(Image $cover): void{
        $this->cover = $cover;
    }

    public function getCover(): ?Image{
        return $this->cover;
    }
}