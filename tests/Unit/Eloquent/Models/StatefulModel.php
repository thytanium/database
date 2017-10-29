<?php

namespace Tests\Unit\Eloquent\Models;

use Thytanium\Database\Eloquent\Models\Stateful;

class StatefulModel extends Stateful
{
    public $defaultState = 1;
    public $guarded = [];
    public $timestamps = false;
}
