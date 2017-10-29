<?php

namespace Tests\Unit\Eloquent\Models;

use Thytanium\Database\Eloquent\Models\Stateful;

class ValidStatesModel extends Stateful
{
    public $validStates = ['Inactive', 'Active'];
    public $timestamps = false;
    public $guarded = [];
}
