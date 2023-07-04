<?php

namespace App\Domains\DataTable\Jobs;

use Lucid\Units\Job;
use Illuminate\Http\Request;
use App\Packages\DataTable\Builder as TableBuilder;
use Closure;

class BuildTableJob extends Job
{
    protected $request;

    protected $routes;

    protected $model;

    protected $resource;

    protected $headers;

    protected $searchHeaders;

    protected $defaultPerPage;

    protected $defaultPerPageItems;

    protected $defaultSort;

    protected $extendQuery;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Request $request,
        array $routes,
        string $model,
        string $resource,
        array $headers,
        int $defaultPerPage = 50,
        array $defaultPerPageItems = [1, 5, 10, 50],
        $extendQuery = null,
        array $defaultSort = ['id' => 'asc'],
        array $searchHeaders = [],
    ) {
        $this->request = $request;
        $this->routes = $routes;
        $this->model = $model;
        $this->resource = $resource;
        $this->headers = $headers;
        $this->defaultPerPage = $defaultPerPage;
        $this->defaultPerPageItems = $defaultPerPageItems;
        $this->extendQuery = $extendQuery;
        $this->defaultSort = $defaultSort;
        $this->searchHeaders = $searchHeaders;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $builder = new TableBuilder;
        $builder->model($this->model);
        $builder->setResource($this->resource);
        $builder->setHeaders($this->headers);
        $builder->setSearchHeaders($this->searchHeaders);
        $builder->setFilters($this->request->input('filter') ?? []);
        $builder->setSort($this->request->input('sort') ?? $this->defaultSort);
        $builder->setSearch($this->request->input('search', ['columns' => [], 'value' => '']));
        $builder->setDefaultPerPage($this->request->input('perPage') ?? $this->defaultPerPage);
        $builder->setDefaultPerPageItems($this->defaultPerPageItems);

        $builder->setRoutes($this->routes);
        if (!blank($this->extendQuery) && $this->extendQuery instanceof Closure) {
            $extendQuery = $this->extendQuery;
            $query = $builder->getQuery();
            $builder->setQuery(
                $extendQuery($query)
            );
        }

        return $builder->build();
    }
}
