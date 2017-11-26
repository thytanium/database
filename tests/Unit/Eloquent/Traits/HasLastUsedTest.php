<?php

namespace Tests\Unit\Eloquent\Traits;

use Carbon\Carbon;
use Tests\Unit\Eloquent\Models\TestModel;
use Thytanium\Tests\DatabaseMigrations;
use Thytanium\Tests\TestCase;

class HasLastUsedTest extends TestCase
{
    use DatabaseMigrations;

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

        $model1 = TestModel::create(['name' => 'model-1', 'last_used' => Carbon::parse('1 month ago')]);
        $model2 = TestModel::create(['name' => 'model-2', 'last_used' => Carbon::parse('now')]);
        $model3 = TestModel::create(['name' => 'model-3', 'last_used' => Carbon::parse('1 week ago')]);

        $models = TestModel::sortByLastUsed()->get();

        $this->assertEquals($models->get(0)->name, 'model-2');
        $this->assertEquals($models->get(1)->name, 'model-3');
        $this->assertEquals($models->get(2)->name, 'model-1');
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
