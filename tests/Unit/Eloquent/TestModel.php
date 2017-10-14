<?php

namespace Tests\Unit\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\HasEmail;
use Thytanium\Database\Eloquent\HasName;

class TestModel extends Model
{
    use HasName, HasEmail;

    public $guarded = [];
    public $timestamps = false;
}
