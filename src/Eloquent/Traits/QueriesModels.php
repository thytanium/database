<?php

namespace Thytanium\Database\Eloquent\Traits;

trait QueriesModels
{
    /**
     * Creates new query Builder instance.
     * 
     * @param  string $className
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function newQuery($className)
    {
        return (new $className)->query();
    }
}
