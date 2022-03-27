<?php

namespace MusicBands\Models;

class ImageDimension extends Model
{
    private $height, $width;

    public function __construct(int $height, int $width){
        $this->height = $height;
        $this->width = $width;
    }

    public function getHeight(): int{
        return $this->height;
    }

    public function getWidth(): int{
        return $this->width;
    }
}
