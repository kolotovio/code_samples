<?php

namespace App\Packages\DataTable;

use Countable;
use Illuminate\Support\Str;

class FilterBuilder
{
    protected $query;
    protected $filters;
    protected $namespace;

    public function __construct($query, $filters, $namespace)
    {
        $this->query = $query;
        $this->filters = $filters;
        $this->namespace = $namespace;
    }

    public function apply()
    {
        foreach ($this->filters as $name => $value) {
            $normailizedName = Str::ucfirst(Str::camel($name));
            $class = $this->namespace . "\\{$normailizedName}";

            if (!class_exists($class)) {
                continue;
            }

            if (!$this->blank($value)) {
                (new $class($this->query))->handle($value);
            }
        }

        return $this->query;
    }

    protected function blank($value)
    {
        if (is_null($value)) {
            return true;
        }

        if (is_string($value)) {
            return trim($value) === '';
        }

        if (is_numeric($value) || is_bool($value)) {
            return false;
        }

        if ($value instanceof Countable) {
            return count($value) === 0;
        }

        return empty($value);
    }
}
