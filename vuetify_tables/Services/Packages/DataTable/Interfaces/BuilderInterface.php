<?php

namespace App\Packages\DataTable\Interfaces;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

interface BuilderInterface
{
    public function model(string $model): void;
    public function setQuery(EloquentBuilder $query): void;
    public function getQuery(): EloquentBuilder;
}
