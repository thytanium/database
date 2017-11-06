<?php

namespace Tests\Unit\Eloquent\Traits;

use Tests\Unit\Eloquent\Models\TestModel;
use Thytanium\Tests\TestCase;

class HasLastUsedTest extends TestCase
{
    /**
     * Test sortByLastUsed() scope.
     * 
     * @return void
     */
    public function test_sort_by_last_used()
    {
        $query = TestModel::sortByLastUsed()->getQuery();

        $this->assertArraySubset([[
            'column' => 'last_used',
            'direction' => 'desc',
        ]], $query->orders);
    }
}
