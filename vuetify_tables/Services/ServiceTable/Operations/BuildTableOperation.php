<?php

namespace App\Services\Delivery\Operations;

use Lucid\Units\Operation;
use Illuminate\Http\Request;
use App\Domains\Respond\Jobs\RespondWithInertiaJob;
use App\Domains\DataTable\Jobs\BuildTableJob;
use Illuminate\Support\Str;

class BuildTableOperation extends Operation
{
    protected $request;

    protected $routes;

    protected $model;

    protected $resource;

    protected $headers;

    protected $searchHeaders;

    protected $defaultPerPage;

    protected $defaultPerPageItems;

    protected $component;

    protected $defaultSort;

    protected $extendQuery;

    /**
     * Create a new operation instance.
     *
     * @return void
     */
    public function __construct(
        Request $request,
        array $routes,
        string $model,
        string $resource,
        array $headers,
        int $defaultPerPage = 10,
        array $defaultPerPageItems = [1, 5, 10, 50],
        string $component = '',
        array $defaultSort = ['id' => 'asc'],
        $extendQuery = null,
        array $searchHeaders = [],
    ) {
        $this->request = $request;
        $this->routes = $routes;
        $this->model = $model;
        $this->resource = $resource;
        $this->headers = $headers;
        $this->defaultPerPage = $defaultPerPage;
        $this->defaultPerPageItems = $defaultPerPageItems;
        $this->component = $component;
        $this->defaultSort = $defaultSort;
        $this->extendQuery = $extendQuery;
        $this->searchHeaders = $searchHeaders;
    }

    /**
     * Execute the operation.
     *
     * @return void
     */
    public function handle()
    {
        $modelName = $this->getModelName();

        $result = $this->run(
            BuildTableJob::class,
            [
                'request' => $this->request,
                'routes' => $this->routes,
                'model' => $this->model,
                'resource' => $this->resource,
                'headers' => $this->headers,
                'defaultPerPage' => $this->defaultPerPage,
                'defaultPerPageItems' => $this->defaultPerPageItems,
                'defaultSort' => $this->defaultSort,
                'extendQuery' => $this->extendQuery,
                'searchHeaders' => $this->searchHeaders,
            ]
        );

        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            return $result;
        }

        $tableName = Str::plural(
            Str::camel($modelName)
        );

        if (blank($this->component)) {
            $this->component = $modelName . '/Index';
        }

        return $this->run(new RespondWithInertiaJob($this->component, [$tableName => $result]));
    }

    protected function getModelName()
    {
        $path = explode('\\', $this->model);
        return array_pop($path);
    }
}
