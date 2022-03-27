<?php

namespace MusicBands\ApiModellers\v1;

use MusicBands\ApiModellers\ApiModeller;
use MusicBands\Models\Image;

/** @property Image $model */
class ImageApiModeller extends ApiModeller
{
    public function toArray(): array
    {
        return [
            'height' => $this->model->getDimension() ? $this->model->getDimension()->getHeight() : null,
            'width' => $this->model->getDimension() ? $this->model->getDimension()->getWidth() : null,
            'url' => $this->model->getUrl()
        ];
    }
}