<?php

namespace Thytanium\Database\Eloquent\Traits;

use Carbon\Carbon;

trait HasLastModified
{
    /**
     * Scope for sorting by last_modified.
     * 
     * @param  Illuminate\Database\Eloquent\Builder  $query
     * @param  boolean $desc
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortByLastModified($query, $desc = true)
    {
        return $query->orderBy($this->getLastModifiedColumn(), $desc ? 'desc' : 'asc');
    }

    /**
     * Get last_modified column name.
     * 
     * @return string
     */
    protected function getLastModifiedColumn()
    {
        return $this->lastModifiedColumn ?? 'last_modified';
    }

    /**
     * Update last_modified column.
     * 
     * @param  Carbon|null $datetime
     * @return $this
     */
    public function updateLastModified(Carbon $datetime  = null)
    {
        $this->{$this->getLastModifiedColumn()} = $datetime ?: Carbon::now()->toDateTimeString();

        return $this;
    }
}
