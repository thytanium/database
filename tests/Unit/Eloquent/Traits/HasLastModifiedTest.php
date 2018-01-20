<?php

namespace Tests\Unit\Eloquent\Traits;

use Carbon\Carbon;
use Tests\Unit\Eloquent\Models\TestModel;
use Thytanium\Tests\{DatabaseMigrations, TestCase};

class HasLastModifiedTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test sortByLastUsed() scope.
     * 
     * @return void
     */
    public function test_sort_by_last_modified()
    {
        $query = TestModel::sortByLastModified()->getQuery();

        $this->assertArraySubset([[
            'column' => 'last_modified',
            'direction' => 'desc',
        ]], $query->orders);

        $model1 = TestModel::create(['name' => 'model-1', 'last_modified' => Carbon::parse('1 month ago')]);
        $model2 = TestModel::create(['name' => 'model-2', 'last_modified' => Carbon::parse('now')]);
        $model3 = TestModel::create(['name' => 'model-3', 'last_modified' => Carbon::parse('1 week ago')]);

        $models = TestModel::sortByLastModified()->get();

        $this->assertEquals($models->get(0)->name, 'model-2');
        $this->assertEquals($models->get(1)->name, 'model-3');
        $this->assertEquals($models->get(2)->name, 'model-1');
    }

    /**
     * Test updateLastModified()
     * 
     * @return void
     */
    public function test_update_last_modified()
    {
        // New model
        $model = new TestModel(['name' => 'model-1']);

        // Datetime object from now
        $now = Carbon::now();

        // Update last used
        $model = $model->updateLastModified($now);

        $this->assertEquals($model->last_modified, $now->toDateTimeString());
    }
}
