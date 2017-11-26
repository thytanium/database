<?php

namespace Thytanium\Database\Eloquent\Traits;

use Carbon\Carbon;

trait HasLastUsed
{
    /**
     * Scope for sorting by last_used.
     * 
     * @param  Illuminate\Database\Eloquent\Builder  $query
     * @param  boolean $desc
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortByLastUsed($query, $desc = true)
    {
        return $query->orderBy($this->getLastUsedColumn(), $desc ? 'desc' : 'asc');
    }

    /**
     * Get last_used column name.
     * 
     * @return string
     */
    protected function getLastUsedColumn()
    {
        return isset($this->lastUsedColumn) ? $this->lastUsedColumn : 'last_used';
    }

    /**
     * Update last_used column.
     * 
     * @param  Carbon|null $datetime
     * @return $this
     */
    public function updateLastUsed(Carbon $datetime  = null)
    {
        $this->{$this->getLastUsedColumn()} = $datetime ?: Carbon::now()->toDateTimeString();

        return $this;
    }
}
