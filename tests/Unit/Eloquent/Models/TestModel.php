<?php

namespace Tests\Unit\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Thytanium\Database\Eloquent\Traits\{
    BelongsToUser,
    Dropdownable,
    HasEmail,
    HasLastUsed,
    HasLastModified,
    HasName,
    HasPosition,
    HasState
};

class TestModel extends Model
{
    use HasName,
        HasEmail,
        HasPosition,
        HasState, 
        HasLastUsed, 
        HasLastModified,
        BelongsToUser,
        Dropdownable;

    public $guarded = [];
    public $timestamps = false;
    public $userClass = 'Tests\Unit\Eloquent\Models\User';
}
