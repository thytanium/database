<?php

namespace Thytanium\Database\Eloquent\Traits;

trait HasEmail
{
    /**
     * Query scope for 'email' attribute.
     * 
     * @param  mixed $query
     * @param  string $email
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Find model by 'email' attribute.
     * 
     * @param  string $email
     * @return static
     */
    public static function findByEmail($email)
    {
        return static::email($email)->first();
    }
}
