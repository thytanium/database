<?php

namespace Tests\Unit\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\Traits\HasPosition;

class PositionPivotModel extends Model
{
    use HasPosition;

    public $positionPivots = ['type_1'];
}
