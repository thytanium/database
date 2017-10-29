<?php

namespace Tests\Unit\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\Traits\HasEmail;
use Thytanium\Database\Eloquent\Traits\HasName;
use Thytanium\Database\Eloquent\Traits\HasPosition;
use Thytanium\Database\Eloquent\Traits\HasState;

class TestModel extends Model
{
    use HasName, HasEmail, HasPosition, HasState;

    public $guarded = [];
    public $timestamps = false;
}
