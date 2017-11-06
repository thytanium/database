<?php

namespace Thytanium\Database\Util;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Arr;

class Dropdown implements ArrayAccess, Arrayable, Countable
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @var Translator
     */
    protected $trans;

    /**
     * New Dropdown instance.
     * 
     * @param array $items
     */
    public function __construct(array $items = [], Translator $trans = null)
    {
        $this->items = $items;
        $this->trans = $trans;
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

    /**
     * Get translator instance.
     * 
     * @return Translator
     */
    public function getTranslator()
    {
        if ($this->trans === null) {
            $this->trans = app('translator');
        }

        return $this->trans;
    }

    /**
     * Set translator instance.
     * 
     * @param Translator $translator
     */
    public function setTranslator(Translator $translator)
    {
        $this->trans = $translator;
    }

    /**
     * Translated dropdown choices.
     * 
     * @param  string $namespace
     * @return $this
     */
    public function lang($namespace)
    {
        $translator = $this->getTranslator();

        $this->items = array_reduce(array_keys($this->items), function ($carry, $key) use ($namespace, $translator) {
            // Get item
            $item = $this->items[$key];

            // Build lang line
            $line = "{$namespace}.{$item}";

            // Replace with translated line
            $carry[$key] = $translator->has($line) ? $translator->get($line) : $item;

            return $carry;
        }, []);

        return $this;
    }

    /**
     * Build Dropdown instance from array.
     * 
     * @param  array  $items
     * @param  string $value
     * @param  string $label
     * @return static
     */
    public static function build($items = [], $value = 'id', $label = 'name')
    {
        $keys = array_keys($items);

        // Create dropdown choices
        $result = array_reduce($keys, function ($carry, $index) use ($items, $value, $label) {
            $item = $items[$index];
            $value = static::extract($item, $index, $value);
            $label = static::extract($item, $index, $label);

            $carry[$value] = $label;

            return $carry;
        }, []);

        return new static($result);
    }

    /**
     * Extract target column/callable.
     * 
     * @param  mixed $item
     * @param  string|callback $target
     * @return string
     */
    protected static function extract($item, $index, $target)
    {
        if (is_callable($target)) {
            return call_user_func($target, $item, $index);
        } else {
            return $item->{$target};
        }
    }
}
