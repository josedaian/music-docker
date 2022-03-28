<?php

namespace MusicBands\ApiModellers;

use MusicBands\Models\Model;

abstract class ApiModeller{
    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public static function collection(array $models): array{
        return array_map(function (Model $model){
            return static::response($model);
        }, $models);
    }

    public static function response(?Model $model): ?array {
        return $model ? (new static($model))->toArray() : null;
    }

    abstract function toArray(): array;
}