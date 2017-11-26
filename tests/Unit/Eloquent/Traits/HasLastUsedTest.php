<?php

namespace Tests\Unit\Eloquent\Traits;

use Carbon\Carbon;
use Tests\Unit\Eloquent\Models\TestModel;
use Thytanium\Tests\DatabaseMigrations;
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

    /**
     * Test updateLastUsed()
     * 
     * @return void
     */
    public function test_update_last_used()
    {
        // New model
        $model = new TestModel(['name' => 'model-1']);

        // Datetime object from now
        $now = Carbon::now();

        // Update last used
        $model = $model->updateLastUsed($now);

        $this->assertEquals($model->last_used, $now->toDateTimeString());
    }
}
