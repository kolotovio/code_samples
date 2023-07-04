<?php

namespace App\Packages\DataTable;

use App\Data\Models\ServiceTable\Brand;
use App\Packages\DataTable\Interfaces\BuilderInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Builder implements BuilderInterface
{
    protected $defaultPerPageItems = [5, 10, 20, 50, 100];

    protected $defaultPerPage = 10;

    protected $headers;

    protected array $searchHeaders;

    protected $settings;

    protected $pagination;

    protected $filters;

    protected $sort;

    protected array $search;

    protected $query;

    protected $routes;

    protected $paginator;

    protected $resource;


    public function model(string $model): void
    {
        $this->setQuery(($model)::query());
    }

    public function setQuery(EloquentBuilder $query): void
    {
        $this->query = $query;
    }

    public function getQuery(): EloquentBuilder
    {
        return $this->query;
    }

    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getSearchHeaders(): array
    {
        return $this->searchHeaders;
    }

    public function setSearchHeaders(array $searchHeaders): void
    {
        $this->searchHeaders = $searchHeaders;
    }

    public function setFilters($filters): void
    {
        $this->filters = $filters;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function setSort($sort): void
    {
        $this->sort = $sort;
    }

    public function getSort(): array
    {
        return $this->sort;
    }

    public function setSearch(array $search): void
    {
        $this->search = $search;
    }

    public function getSearch(): array
    {
        return $this->search;
    }

    public function setDefaultPerPageItems(array $defaultPerPageItems): void
    {
        $this->defaultPerPageItems = $defaultPerPageItems;
    }

    public function getDefaultPerPageItems(): array
    {
        return $this->defaultPerPageItems;
    }

    public function setDefaultPerPage(int $defaultPerPage): void
    {
        $this->defaultPerPage = $defaultPerPage;
    }

    public function getDefaultPerPage(): int
    {
        return $this->defaultPerPage;
    }

    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function setResource(string $resource): void
    {
        if (class_exists($resource)) {
            $this->resource = $resource;
        }
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    protected function getPaginator(): LengthAwarePaginator
    {
        if ($this->pagination instanceof LengthAwarePaginator) {
            return $this->paginator;
        }

        $this->paginator = $this->query->paginate($this->defaultPerPage)->withQueryString();

        return $this->paginator;
    }

    protected function applySort()
    {
        $q = $this->getQuery();
        $model = $q->getModel();

        foreach ($this->getSort() as $sortKey => $sortOrder) {
            if ($sortKey === 'name' && $model instanceof Brand) {
                $q->orderBy('name->ru', $sortOrder);
            } else if (stripos($sortKey, 'id') !== false) {
                $q->orderByRaw("cast($sortKey as unsigned) $sortOrder");
            } else {
                $q->orderBy($sortKey, $sortOrder);
            }
        }
        $this->setQuery($q);
    }

    protected function applySearch()
    {
        $q = $this->getQuery();
        $model = $q->getModel();

        $searchColumns = $this->getSearch()['columns'] ?? [];
        $value = $this->getSearch()['value'] ?? [];

        $q->where(function($q) use ($searchColumns, $value, $model) {
            foreach ($searchColumns as $column) {
                if ($column === 'name' && $model instanceof Brand) {
                    $q->orWhere('name->ru', 'like', "%$value%");
                } else {
                    $q->orWhere($column, 'like', "%$value%");
                }
            }
        });

        $this->setQuery($q);
    }

    public function build()
    {
        $this->applySort();
        $this->applySearch();
        $paginator = $this->getPaginator();

        if ($paginator->currentPage() > $paginator->lastPage()) {
            return redirect()->to($paginator->previousPageUrl());
        }

        return [
            'items' => $this->resource ? ($this->resource)::collection($paginator->items()) : $paginator->items(),
            'pagination' => [
                'nextPageUrl' => $paginator->nextPageUrl(),
                'previousPageUrl' => $paginator->previousPageUrl(),
                'perPage' => $paginator->perPage(),
                'count' => $paginator->count(),
                'currentPage' => $paginator->currentPage(),
                'total' => $paginator->total(),
            ],
            'settings' => [
                'defaultPerPageItems' => $this->defaultPerPageItems,
                'defaultSort' => $this->getSort(),
                'search' => $this->getSearch(),
            ],
            'routes' => $this->getRoutes(),
            'headers' => $this->getHeaders(),
            'searchHeaders' => $this->getSearchHeaders(),
        ];
    }
}
