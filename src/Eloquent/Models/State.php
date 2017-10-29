<?php

namespace Thytanium\Database\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\Traits\HasName;

class State extends Model
{
    use HasName;

    public $timestamps = false;
    public $guarded = [];
}
