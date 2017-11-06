<?php

namespace Thytanium\Database\Eloquent\Traits;

use Thytanium\Database\Util\Dropdown;

trait Dropdownable
{
    /**
     * Scope for dropdown.
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  string|int|callable $value
     * @param  string|int|callable $label
     * @return Dropdown
     */
    public function scopeDropdown($query, $value = 'id', $label = 'name')
    {
        return Dropdown::build($query->get()->all(), $value, $label);
    }
}
