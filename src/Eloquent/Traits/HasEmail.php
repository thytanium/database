<?php

namespace Thytanium\Database\Eloquent\Traits;

trait HasEmail
{
    /**
     * Query scope for 'email' attribute.
     * 
     * @param  mixed $query
     * @param  string $email
     * @param  string $column
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeEmail($query, $email, $column = 'email')
    {
        return $query->where($column, $email);
    }

    /**
     * Find model by 'email' attribute.
     * 
     * @param  string $email
     * @param  string $column
     * @return static
     */
    public static function findByEmail($email, $column = 'email')
    {
        return static::email($email, $column)->first();
    }
}
