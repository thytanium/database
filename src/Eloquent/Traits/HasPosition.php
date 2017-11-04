<?php

namespace Thytanium\Database\Eloquent\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Thytanium\Database\Exceptions\PivotValuesException;

trait HasPosition
{
    /**
     * Temp big position to work with when moving up/down.
     * @var integer
     */
    protected static $insanePosition = 99999999;

    /**
     * Query scope for static::$positionColumn attribute.
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  int $position
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopePosition($query, $position)
    {
        return $query->where($this->getPositionColumn(), $position);
    }

    /**
     * Query scope: position greater than X.
     * 
     * @param  Illuminate\Database\Query\Builder $query
     * @param  integer $position
     * @return Illuminate\Database\Query\Builder
     */
    public function scopePositionGt($query, $position)
    {
        return $query->where($this->getPositionColumn(), '>', $position);
    }

    /**
     * Query scope: position greater than or equal to X.
     * 
     * @param  Illuminate\Database\Query\Builder $query
     * @param  integer $position
     * @return Illuminate\Database\Query\Builder
     */
    public function scopePositionGte($query, $position)
    {
        return $query->where($this->getPositionColumn(), '>=', $position);
    }

    /**
     * Query scope: position less than X.
     * 
     * @param  Illuminate\Database\Query\Builder $query
     * @param  integer $position
     * @return Illuminate\Database\Query\Builder
     */
    public function scopePositionLt($query, $position)
    {
        return $query->where($this->getPositionColumn(), '<', $position);
    }

    /**
     * Query scope: position less than or equal to X.
     * 
     * @param  Illuminate\Database\Query\Builder $query
     * @param  integer $position
     * @return Illuminate\Database\Query\Builder
     */
    public function scopePositionLte($query, $position)
    {
        return $query->where($this->getPositionColumn(), '<=', $position);
    }

    /**
     * Query scope: position between.
     * 
     * @param  Illuminate\Database\Query\Builder $query
     * @param  integer $one
     * @param  integer $two
     * @return Illuminate\Database\Query\Builder
     */
    public function scopePositionBetween($query, $one, $two)
    {
        return $query->whereBetween($this->getPositionColumn(), [$one, $two]);
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
        if ($this->{$this->getPositionColumn()} > 1) {
            return $this->movePosition(-$step);
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
        return $this->movePosition($step);
    }

    /**
     * Move this model in order.
     * 
     * @param  integer  $step
     * @return static
     */
    public function movePosition($step)
    {
        // Column name
        $column = $this->getPositionColumn();

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
        $taken = static::positionTaken($target);

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
        $step = $position - $this->{$this->getPositionColumn()};

        return $this->movePosition($step);
    }

    /**
     * Move to first position.
     * 
     * @return static
     */
    public function moveFirst()
    {
        return $this->moveTo(1);
    }

    /**
     * Move to last position.
     * 
     * @return static
     */
    public function moveLast()
    {
        $target = static::max($this->getPositionColumn());

        return $this->moveTo($target);
    }

    /**
     * Swap positions.
     * 
     * @param  integer|static $position
     * @return static
     */
    public function swapPositions($position)
    {
        if (is_object($position) && $position instanceof Model) {
            $target = $position;
        } else {
            $target = static::position($position)->first();
        }

        if (null !== $target) {
            DB::beginTransaction();

            // Get position column name
            $positionColumn = $this->getPositionColumn();

            // Current position
            $current = $this->{$positionColumn};
            $new = $target->{$positionColumn};

            // Assign temporary position
            $this->tempPosition();

            // Target position
            $target->{$positionColumn} = $current;
            $target->save();

            // New position
            $this->{$positionColumn} = $new;
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
        $this->{$this->getPositionColumn()} = static::$insanePosition;
        $this->save();
    }

    /**
     * Determines whether a specific position has already been taken.
     * 
     * @param  integer $position
     * @return boolean
     */
    public static function positionTaken($position)
    {
        return static::position($position)->count() > 0;
    }

    /**
     * Determine next position.
     *
     * @param  array $input Pivot values
     * @return integer
     * @throws MissingPivotValuesException
     */
    public static function nextPosition(array $input = [])
    {
        return (new static)->determineNextPosition($input);
    }

    /**
     * Determine next position.
     * 
     * @param  array  $input
     * @return integer
     * @throws MissingPivotValuesException
     */
    protected function determineNextPosition(array $input = [])
    {
        if (isset($this->positionPivots) && count($input) === 0) {
            throw new PivotValuesException('Pivot values must be specified.');
        }

        // Map pivots - remove extra information
        $input = $this->mapPivots($input);

        if ($this->enoughPivotValues($input) !== true) {
            throw new PivotValuesException('There are missing pivot values.');
        }

        return static::max($this->getPositionColumn()) + 1;
    }

    /**
     * Determines if pivot values are complete.
     * 
     * @param  array  $input
     * @return boolean
     */
    protected function enoughPivotValues(array $input)
    {
        return count($input) === count($this->positionPivots);
    }

    /**
     * Get position column name.
     * 
     * @return string
     */
    protected function getPositionColumn()
    {
        return isset($this->positionColumn) ? $this->positionColumn : 'position';
    }

    /**
     * Map position pivots values with input array.
     * 
     * @param  array  $input
     * @return array
     */
    protected function mapPivots(array $input)
    {
        if (isset($this->positionPivots)) {
            return array_filter($input, function ($value, $column) {
                return in_array($column, $this->positionPivots);
            }, ARRAY_FILTER_USE_BOTH);
        } else {
            return [];
        }
    }
}
