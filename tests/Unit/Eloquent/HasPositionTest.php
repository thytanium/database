<?php

namespace Tests\Unit\Eloquent;

use Illuminate\Database\Query\Builder;
use Thytanium\Tests\DatabaseMigrations;
use Thytanium\Tests\TestCase;

class HasPositionTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test position() returns a valid query.
     * 
     * @return void
     */
    public function test_valid_scope()
    {
        // Apply the query scope
        $query = TestModel::position(1)->getQuery();

        $this->assertInstanceOf(Builder::class, $query);
        $this->assertCount(1, $query->wheres);
        $this->assertArraySubset([
            'column' => 'position',
            'operator' => '=',
            'value' => 1,
        ], $query->wheres[0]);
    }

    /**
     * Test moveUp() doesn't move model at top.
     * 
     * @return void
     */
    public function test_move_up_doesnt_move()
    {
        // Create model
        $model = TestModel::create(['position' => 1]);
        $model->moveUp();

        $this->assertEquals(1, $model->position);
    }

    /**
     * Test valid moveUp() with 2 models.
     * 
     * @return void
     */
    public function test_move_up_with_two_models()
    {
        TestModel::create(['name' => 'model-1', 'position' => 1]);
        $two = TestModel::create(['name' => 'model-2', 'position' => 2]);

        // Move up
        $two->moveUp();

        $one = TestModel::findByName('model-1');

        $this->assertEquals(2, $one->position);
        $this->assertEquals(1, $two->position);
    }

    /**
     * Test valid moveDown() with 2 models.
     * 
     * @return void
     */
    public function test_move_down_with_two_models()
    {
        $one = TestModel::create(['name' => 'model-1', 'position' => 1]);
        TestModel::create(['name' => 'model-2', 'position' => 2]);

        // Move up
        $one->moveDown();

        $two = TestModel::findByName('model-2');

        $this->assertEquals(1, $two->position);
        $this->assertEquals(2, $one->position);
    }

    /**
     * Test valid moveUp() with step.
     * 
     * @return void
     */
    public function test_move_up_with_step()
    {
        TestModel::create(['name' => 'model-1', 'position' => 1]);
        TestModel::create(['name' => 'model-2', 'position' => 2]);
        $three = TestModel::create(['name' => 'model-3', 'position' => 3]);

        // Move up
        $three->moveUp(2);

        $one = TestModel::findByName('model-1');
        $two = TestModel::findByName('model-2');

        $this->assertEquals(1, $three->position);
        $this->assertEquals(2, $one->position);
        $this->assertEquals(3, $two->position);
    }

    /**
     * Test valid moveDown() with step.
     * 
     * @return void
     */
    public function test_move_down_with_step()
    {
        $one = TestModel::create(['name' => 'model-1', 'position' => 1]);
        TestModel::create(['name' => 'model-2', 'position' => 2]);
        TestModel::create(['name' => 'model-3', 'position' => 3]);

        // Move down
        $one->moveDown(2);

        $two = TestModel::findByName('model-2');
        $three = TestModel::findByName('model-3');

        $this->assertEquals(1, $two->position);
        $this->assertEquals(2, $three->position);
        $this->assertEquals(3, $one->position);
    }

    /**
     * Test moveUp() from position 2 with step 2 goes to position 1.
     * 
     * @return void
     */
    public function test_move_up_from_2_step_2_returns_1()
    {
        TestModel::create(['name' => 'model-1', 'position' => 1]);
        $two = TestModel::create(['name' => 'model-2', 'position' => 2]);
        TestModel::create(['name' => 'model-3', 'position' => 3]);

        // Move up
        $two->moveUp(2);

        $one = TestModel::findByName('model-1');
        $three = TestModel::findByName('model-3');

        $this->assertEquals(1, $two->position);
        $this->assertEquals(2, $one->position);
        $this->assertEquals(3, $three->position);
    }
}
