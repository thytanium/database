<?php

namespace Thytanium\Database\Eloquent\Traits;

trait HasName
{
    /**
     * Query scope for 'name' attribute.
     * 
     * @param  mixed $query
     * @param  string $name
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * Find model by 'name' attribute.
     * 
     * @param  string $name
     * @return static
     */
    public static function findByName($name)
    {
        return static::name($name)->first();
    }
}
