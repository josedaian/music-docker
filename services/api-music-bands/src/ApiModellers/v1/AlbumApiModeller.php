<?php

namespace MusicBands\ApiModellers\v1;

use MusicBands\ApiModellers\ApiModeller;
use MusicBands\Models\Album;

/** @property Album $model */
class AlbumApiModeller extends ApiModeller
{
    public function toArray(): array
    {
        return [
            'name' => $this->model->getName(),
            'released' => $this->model->getReleasedAt() ? $this->model->getReleasedAt()->format('d-m-Y') : null,
            'tracks' => $this->model->getNumberOfTracks(),
            'cover' => ImageApiModeller::response($this->model->getCover())
        ];
    }
}