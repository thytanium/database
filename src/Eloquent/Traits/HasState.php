<?php

namespace Thytanium\Database\Eloquent\Traits;

use Thytanium\Database\Eloquent\Models\State;

trait HasState
{
    /**
     * Query scope for Inactive state.
     * 
     * @param  Illuminate\Database\Query\Builder $query
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('state_id', State::$inactive);
    }

    /**
     * Query scope for Active state.
     * 
     * @param  Illuminate\Database\Query\Builder $query
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('state_id', State::$active);
    }

    /**
     * Query scope for Banned state.
     * 
     * @param  Illuminate\Database\Query\Builder $query
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeBanned($query)
    {
        return $query->where('state_id', State::$banned);
    }

    /**
     * Query scope for Suspended state.
     * 
     * @param  Illuminate\Database\Query\Builder $query
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeSuspended($query)
    {
        return $query->where('state_id', State::$suspended);
    }

    /**
     * Query scope for Accepted state.
     * 
     * @param  Illuminate\Database\Query\Builder $query
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeAccepted($query)
    {
        return $query->where('state_id', State::$accepted);
    }
}
