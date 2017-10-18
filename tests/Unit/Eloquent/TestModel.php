<?php

namespace Tests\Unit\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\HasEmail;
use Thytanium\Database\Eloquent\HasName;
use Thytanium\Database\Eloquent\HasPosition;

class TestModel extends Model
{
    use HasName, HasEmail, HasPosition;

    public $guarded = [];
    public $timestamps = false;
}
