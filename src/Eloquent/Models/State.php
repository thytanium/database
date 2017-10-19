<?php

namespace Thytanium\Database\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\Traits\HasName;

class State extends Model
{
    use HasName;

    public static $inactive = 0;
    public static $active = 1;
    public static $banned = 2;
    public static $suspended = 3;
    public static $accepted = 4;

    public $timestamps = false;
    public $fillable = ['id', 'name'];
}
