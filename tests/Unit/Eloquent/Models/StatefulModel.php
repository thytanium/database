<?php

namespace Tests\Unit\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\Traits\HasState;

class StatefulModel extends Model
{
    use HasState;

    public $validStates = ['Inactive', 'Active'];
    public $timestamps = false;
    public $guarded = [];
}
