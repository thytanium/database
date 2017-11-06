<?php

namespace Thytanium\Database\Util;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class Dropdown implements ArrayAccess, Arrayable, Countable
{
    /**
     * @var array
     */
    protected $items;

    /**
     * New Dropdown instance.
     * 
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Determine if an offset exists.
     * 
     * @param  mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * Get an offset.
     * 
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * Set an offset.
     * 
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * Unset an offset.
     * 
     * @param  mixed $offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * Return dropdown size.
     * 
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Convert Dropdown to array.
     * 
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * Append an item.
     * 
     * @param  string $label
     * @param  string $value
     * @return $this
     */
    public function append($label, $value = '')
    {
        $this->items = Arr::add($this->items, $value, $label);

        return $this;
    }

    /**
     * Prepend an item.
     * 
     * @param  string $label
     * @param  string $value
     * @return $this
     */
    public function prepend($label, $value = '')
    {
        $this->items = Arr::prepend($this->items, $label, $value);

        return $this;
    }
}
