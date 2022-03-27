<?php

namespace MusicBands\Models;

class Image extends Model
{
    private $url, $dimension;

    public function __construct(?string $url){
        $this->url = $url;
    }

    public function setDimensions(ImageDimension $dimension){
        $this->dimension = $dimension;
    }

    public function getDimension(): ?ImageDimension{
        return $this->dimension;
    }

    public function getUrl(): ?string {
        return $this->url;
    }
}