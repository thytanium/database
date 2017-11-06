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
        // Get current query result
        $result = $query->get()->all();

        // Create dropdown choices
        $result = array_reduce(array_keys($result), function ($carry, $index) use ($result, $value, $label) {
            // Get item
            $item = $result[$index];

            // Get value
            if (is_callable($value)) {
                $value = $value($item, $index);
            } else {
                $value = $item->{$value};
            }

            // Get label
            if (is_callable($label)) {
                $label = $label($item, $index);
            } else {
                $label = $item->{$label};
            }

            $carry[$value] = $label;
            return $carry;
        }, []);

        return new Dropdown($result);
    }
}
