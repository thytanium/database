<?php

namespace Thytanium\Database\Eloquent\Traits;

use Illuminate\Database\Eloquent\Model;

trait BelongsToUser
{
    /**
     * Relationship with User model.
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo($this->getUserClass());
    }

    /**
     * Get User model class name.
     * 
     * @return string
     */
    protected function getUserClass()
    {
        return isset($this->userClass) ? $this->userClass : 'App/User';
    }

    /**
     * Set User model class name.
     * 
     * @param string $class
     */
    public function setUserClass($class)
    {
        $this->userClass = $class;
    }

    /**
     * Get user_id column name.
     * 
     * @return string
     */
    protected function getUserColumn()
    {
        return isset($this->userColumn) ? $this->userColumn : 'user_id';
    }

    /**
     * Scope for user.
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  Model|int $user
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $user)
    {
        if (is_object($user) && $user instanceof Model) {
            return $query->where($this->getUserColumn(), $user->getKey());
        } else {
            return $query->where($this->getUserColumn(), $user);
        }
    }

    /**
     * Set user for this model.
     * 
     * @param Model $user
     */
    public function setUser($user)
    {
        $this->user()->associate($user);

        return $this;
    }
}
