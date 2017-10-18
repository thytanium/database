<?php

namespace Thytanium\Database\Eloquent;

use Illuminate\Support\Facades\DB;

trait HasPosition
{
    /**
     * Field name.
     * @var string
     */
    public static $positionColumn = 'position';

    /**
     * Temp big position to work with when moving up/down.
     * @var integer
     */
    protected static $insanePosition = 99999999;

    /**
     * Query scope for static::$positionColumn attribute.
     * 
     * @param  mixed $query
     * @param  int $position
     * @return Illuminate\Database\Query\Builder
     */
    public function scopePosition($query, $position)
    {
        return $query->where(static::$positionColumn, $position);
    }

    public function scopePositionGt($query, $position)
    {
        return $query->where(static::$positionColumn, '>', $position);
    }

    public function scopePositionGte($query, $position)
    {
        return $query->where(static::$positionColumn, '>=', $position);
    }

    public function scopePositionLt($query, $position)
    {
        return $query->where(static::$positionColumn, '<', $position);
    }

    public function scopePositionLte($query, $position)
    {
        return $query->where(static::$positionColumn, '<=', $position);
    }

    public function scopePositionBetween($query, $one, $two)
    {
        return $query->whereBetween(static::$positionColumn, [$one, $two]);
    }

    /**
     * Move this model up in order.
     * 
     * @param  integer $step
     * @param  boolean $force
     * @return boolean
     */
    public function moveUp($step = 1, $force = false)
    {
        // Only when position > 1 or being forced
        if ($this->{static::$positionColumn} > 1 || $force) {
            return $this->move(-$step, $force);
        }
    }

    /**
     * Move this model down in order.
     * 
     * @param  integer $step
     * @param  boolean $force
     * @return boolean
     */
    public function moveDown($step = 1, $force = false)
    {
        return $this->move($step, $force);
    }

    /**
     * Move this model in order.
     * 
     * @param  integer  $step
     * @param  boolean $force
     * @return boolean
     */
    public function move($step, $force = false)
    {
        // Column name
        $column = static::$positionColumn;

        // Save current position
        $old = $this->{$column};

        // Temp position
        $this->tempPosition();

        // Going up or down?
        $goingUp = $step > 0;
        $step = abs($step);

        // Lower and higher positions to update
        $lower = $goingUp ? $old + 1 : $old - $step;
        $higher = $goingUp ? $old + $step : $old - 1;
        $operator = $goingUp ? '-' : '+';

        // Update other models
        static::positionBetween($lower, $higher)
        ->update([
            $column => DB::raw("{$column} {$operator} 1")
        ]);

        // Calculate new position
        if ($goingUp) {
            $position = $old + $step;
        } else {
            if (($position = $old - $step) < 1) {
                $position = 1;
            }
        }

        $this->{$column} = $position;
        return $this->save();
    }

    /**
     * Sets a temporary high position.
     * To avoid crashes when 'position' is a unique field in database.
     * @return boolean
     */
    protected function tempPosition()
    {
        $this->{static::$positionColumn} = static::$insanePosition;
        $this->save();
    }
}
