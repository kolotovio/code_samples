<?php

namespace App\Packages\DataTable\Interfaces;

interface FilterInterface
{
    public function handle($value): void;
}
