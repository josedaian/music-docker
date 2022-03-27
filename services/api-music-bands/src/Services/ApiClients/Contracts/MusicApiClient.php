<?php

namespace MusicBands\Services\ApiClients\Contracts;

interface MusicApiClient
{
    public function searchAlbums(string $search): array;
}