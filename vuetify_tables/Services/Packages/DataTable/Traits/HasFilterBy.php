<?php

namespace App\Packages\DataTable\Traits;

use App\Packages\DataTable\FilterBuilder;

trait HasFilterBy
{
    public function scopeFilterBy($query, $filters)
    {
        $path = explode('\\', get_class($this));
        $namespace = 'App\Data\Filters\\' . array_pop($path);
        $filter = new FilterBuilder($query, $filters, $namespace);
        return $filter->apply();
    }
}
