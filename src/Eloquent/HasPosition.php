<?php

namespace Thytanium\Database\Eloquent;

use Illuminate\Database\Eloquent\Model;
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
     * @return static
     */
    public function moveUp($step = 1)
    {
        // Only when position > 1 or being forced
        if ($this->{static::$positionColumn} > 1) {
            return $this->move(-$step);
        }

        return $this;
    }

    /**
     * Move this model down in order.
     * 
     * @param  integer $step
     * @return static
     */
    public function moveDown($step = 1)
    {
        return $this->move($step);
    }

    /**
     * Move this model in order.
     * 
     * @param  integer  $step
     * @return static
     */
    public function move($step)
    {
        // Column name
        $column = static::$positionColumn;

        // Save current position
        $old = $this->{$column};

        // Going up or down?
        $goingUp = $step < 0;

        // Calculate target
        $target = $old + $step;
        // When going up and target < 1 then assign position 1.
        if ($goingUp && $target < 1) {
            $target = 1;
        }

        // Check if position is taken
        $taken = $this->taken($target);

        // Work with absolute step value from now on
        // since we already now if we're going up or down.
        $step = abs($step);

        DB::beginTransaction();

        // Move other models from their positions
        // when the target position is taken.
        if ($taken) {
            // Take a temporary position
            $this->tempPosition();

            // Lower and higher positions to update
            $lower = $goingUp ? $target : $old + 1;
            $higher = $goingUp ? $old - 1 : $target;
            $operator = $goingUp ? '+' : '-';

            // Update other models
            static::positionBetween($lower, $higher)
                ->update([
                    $column => DB::raw("{$column} {$operator} 1"),
                ]);
        }

        $this->{$column} = $target;
        $this->save();

        DB::commit();

        return $this;
    }

    /**
     * Move this model to specific position.
     * 
     * @param  integer $position
     * @return static
     */
    public function moveTo($position)
    {
        $step = $position - $this->{static::$positionColumn};

        return $this->move($step);
    }

    /**
     * Swap positions.
     * 
     * @param  integer|static $position
     * @return static
     */
    public function swap($position)
    {
        if (is_object($position) && $position instanceof Model) {
            $target = $position;
        } else {
            $target = static::position($position)->first();
        }

        if (null !== $target) {
            DB::beginTransaction();

            // Current position
            $current = $this->{static::$positionColumn};
            $new = $target->{static::$positionColumn};

            // Assign temporary position
            $this->tempPosition();

            // Target position
            $target->{static::$positionColumn} = $current;
            $target->save();

            // New position
            $this->{static::$positionColumn} = $new;
            $this->save();

            DB::commit();
        }

        return $this;
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

    /**
     * Determines whether a specific position has already been taken.
     * 
     * @param  integer $position
     * @return boolean
     */
    public function taken($position)
    {
        return static::position($position)->count() > 0;
    }
}
