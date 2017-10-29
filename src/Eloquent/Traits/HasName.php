<?php

namespace Thytanium\Database\Eloquent\Traits;

trait HasName
{
    /**
     * Query scope for 'name' attribute.
     * 
     * @param  mixed $query
     * @param  string $name
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeName($query, $name)
    {
        if (is_array($name)) {
            return $query->whereIn('name', $name);
        } else {
            return $query->where('name', $name);
        }
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
