<?php

namespace App\Services\ServiceTable\Http\Controllers;

use App\Data\Models\Mall;
use App\Services\ServiceTable\Features\CreateMallFeature;
use App\Services\ServiceTable\Features\DeleteMallFeature;
use App\Services\ServiceTable\Features\EditMallFeature;
use App\Services\ServiceTable\Features\SearchMallFeature;
use App\Services\ServiceTable\Features\TableMallFeature;
use App\Services\ServiceTable\Features\StoreMallFeature;
use App\Services\ServiceTable\Features\UpdateMallFeature;
use Lucid\Units\Controller;
use Illuminate\Http\Request;

class MallController extends Controller
{
    public function index()
    {
        return $this->serve(TableMallFeature::class);
    }

    public function create()
    {
        return $this->serve(CreateMallFeature::class);
    }

    public function store()
    {
        return $this->serve(StoreMallFeature::class);
    }

    public function edit(Mall $mall)
    {
        return $this->serve(EditMallFeature::class, ['mall' => $mall]);
    }

    public function update(Mall $mall)
    {
        return $this->serve(UpdateMallFeature::class, ['mall' => $mall]);
    }

    public function destroy(Request $request)
    {
        return $this->serve(DeleteMallFeature::class, ['ids' => $request->ids]);
    }

    public function search()
    {
        return $this->serve(SearchMallFeature::class);
    }
}
