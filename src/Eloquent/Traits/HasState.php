<?php

namespace Thytanium\Database\Eloquent\Traits;

use Thytanium\Database\Eloquent\Models\State;
use Thytanium\Database\Exceptions\InvalidStateException;

trait HasState
{
    /**
     * Mapped valid states.
     *
     * @var array
     */
    protected $mapped = null;

    /**
     * Query scope for specific state.
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  int $state
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasState($query, $state)
    {
        return $query->when(!is_null($state), function ($query) use ($state) {
            if (is_string($state)) {
                return $query->whereHas('state', function ($query) use ($state) {
                    $query->name($state);
                });
            } else {
                return $query->where('state_id', $state);
            }
        })
        ->when(is_null($state), function ($query) {
            return $query->whereNull('state_id');
        });
    }

    /**
     * Set new state to this model.
     * 
     * @param int $state
     * @return  static
     */
    public function setState($state)
    {
        if ($this->validState($state)) {
            if (is_string($state)) {
                $this->state()->associate(State::findByName(studly_case($state)));
            } else {
                $this->state_id = $state;
            }
        } else {
            throw new InvalidStateException;
        }

        return $this;
    }

    /**
     * Determine if a new state is valid for this model.
     * 
     * @param  int $state
     * @return boolean
     */
    public function validState($state)
    {
        // Map valid states
        if (null === $this->mapped) {
            $this->mapValidStates();
        }

        // If valid states have been defined
        // and the new state is within those states
        return $this->mapped->count() === 0 ||
            $this->mapped->first(function ($object) use ($state) {
                return (is_string($state) && $object->name === studly_case($state)) || 
                    (is_integer($state) && (int) $object->id === $state);
            }) !== null;
    }

    /**
     * Check if model has a state.
     * 
     * @param  string|int $state
     * @return boolean
     */
    public function isState($state)
    {
        if (is_null($this->state_id)) {
            return is_null($state);
        } else {
            if (is_string($state)) {
                return (string) $this->state->name === studly_case($state);
            } else {
                return (int) $this->state_id === $state;
            }
        }
    }

    /**
     * Relationship with State.
     * 
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Map valid states into their respective models.
     * 
     * @return void
     */
    protected function mapValidStates()
    {
        $this->mapped = collect();

        $states = array_map('studly_case', $this->validStates ?: []);
        $names = array_filter($states, 'is_string');
        $ids = array_filter($states, 'is_integer');

        if (count($names)) {
            $this->mapped = $this->mapped->merge(State::name($names)->get());
        }

        if (count($ids)) {
            $this->mapped = $this->mapped->merge(State::whereIn('id', $ids)->get());
        }
    }
}
