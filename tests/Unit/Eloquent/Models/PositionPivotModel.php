<?php

namespace Tests\Unit\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\Traits\HasName;
use Thytanium\Database\Eloquent\Traits\HasPosition;

class PositionPivotModel extends Model
{
    use HasPosition, HasName;

    public $positionPivots = ['type_1'];
    public $guarded = [];
    public $timestamps = false;
}
