<?php

namespace App\Services\ServiceTable\Features;

use App\Data\Models\Mall;
use Illuminate\Http\Request;
use Lucid\Units\Feature;
use App\Services\Delivery\Operations\BuildTableOperation;
use App\Services\ServiceTable\Http\Resources\MallResource;

class TableMallFeature extends Feature
{
    public function handle(Request $request)
    {
        return $this->run(
            BuildTableOperation::class,
            [
                'request' => $request,
                'routes' => [
                    'index' => [
                        'route' => 'mall.index'
                    ],
                    'edit' => [
                        'route' => 'mall.edit'
                    ],
                    'create' => [
                        'route' => 'mall.create'
                    ],
                    'delete' => [
                        'route' => 'mall.delete'
                    ],
                ],
                'model' => Mall::class,
                'resource' => MallResource::class,
                'headers' => [
                    ['text' => '#', 'value' => 'id'],
                    ['text' => 'Имя', 'value' => 'name'],
                    ['text' => 'Код в 1С', 'value' => 'id_1c'],
                ],
                'searchHeaders' => [
                    ['text' => '#', 'value' => 'id'],
                    ['text' => 'Имя', 'value' => 'name'],
                    ['text' => 'Код в 1С', 'value' => 'id_1c'],
                ]
            ]
        );
    }
}
