<?php

namespace MusicBands\Services\ApiClients\ApiConfigs;

abstract class ApiConfig
{
    /** Common properties */
    const API_URL = 'api_url';
    const TIMEOUT = 'timeout';

    /** @var array  */
    private $properties = [];

    protected function __construct(array $properties){
        $this->properties = $properties;
    }

    function setProperty(string $name, $value): void{
        $this->properties[$name] = $value;
    }

    function getProperty(string $name) {
        return $this->properties[$name] ?? null;
    }

    function getAllProperties(): array {
        return $this->properties;
    }

    abstract static function buildConfig(): ApiConfig;
}