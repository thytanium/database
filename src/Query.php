<?php

namespace Thytanium\Database\Query;

use Illuminate\Database\Query\Builder;

abstract class Query
{
    public $defaultSort = [];

    /**
     * Query builder instance.
     * 
     * @var Builder
     */
    protected $query;

    /**
     * New Query instance.
     * 
     * @param Builder $query
     * @param array   $defaultSort
     */
    public function __construct(Builder $query, $defaultSort = [])
    {
        $this->query = $query;
        $this->defaultSort = $defaultSort ?: ['id', 'asc'];
    }

    /**
     * Base query.
     * 
     * @return $this
     */
    public function query()
    {
        return $this;
    }

    /**
     * Perfom search operations on query.
     * 
     * @return $this
     */
    public function search()
    {
        return $this;
    }

    /**
     * Perform sorting.
     * 
     * @return $this
     */
    public function sort()
    {
        list($key, $type) = $this->defaultSort;

        if (is_array($this->defaultSort) && count($this->defaultSort) === 2) {
            $this->query->orderBy($key, $type);
        }

        return $this;
    }

    /**
     * Get query builder instance.
     * 
     * @return Builder
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Performs whole query stack.
     * 
     * @return Builder
     */
    public function stack()
    {
        return $this->query()->search()->sort()->getQuery();
    }

    /**
     * Get query stack without sorting.
     * 
     * @return Builder
     */
    public function withoutSort()
    {
        return $this->query()->search()->getQuery();
    }
}
