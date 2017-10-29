<?php

namespace Tests\Unit\Eloquent\Traits;

use Illuminate\Database\Query\Builder;
use Tests\Unit\Eloquent\Models\StatefulModel;
use Tests\Unit\Eloquent\Models\TestModel;
use Thytanium\Database\Eloquent\Models\State;
use Thytanium\Database\Seeders\StateSeeder;
use Thytanium\Tests\DatabaseMigrations;
use Thytanium\Tests\TestCase;

class HasStateTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Set up tests.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Seed states
        (new StateSeeder)->run();
    }

    /**
     * Test hasState() scope.
     *
     * @dataProvider stateProvider
     * @return void
     */
    public function test_state_scope($state)
    {
        $query = TestModel::hasState($state)->getQuery();

        $this->assertInstanceOf(Builder::class, $query);
        
        if (is_string($state)) {
            $wheres = $query->wheres[0]['query']->wheres;

            $this->assertCount(2, $wheres);
            $this->assertArraySubset([
                'type' => 'Column',
                'first' => 'test_models.state_id',
                'operator' => '=',
                'second' => 'states.id',
            ], $wheres[0]);
            $this->assertArraySubset([
                'type' => 'Basic',
                'column' => 'name',
                'operator' => '=',
                'value' => $state,
            ], $wheres[1]);
        } else if (is_null($state)) {
            $this->assertCount(1, $query->wheres);
            $this->assertArraySubset([
                'type' => 'Null',
                'column' => 'state_id',
            ], $query->wheres[0]);
        } else {
            $this->assertCount(1, $query->wheres);
            $this->assertArraySubset([
                'column' => 'state_id',
                'operator' => '=',
                'value' => $state,
            ], $query->wheres[0]);
        }
    }

    /**
     * Test setState().
     *
     * @dataProvider stateProvider
     * @return void
     */
    public function test_set_state($state)
    {
        $model = new TestModel;
        $model->setState($state);

        $this->assertTrue($model->isState($state));
    }

    /**
     * Test provider for test_state_scope().
     * 
     * @return array
     */
    public function stateProvider()
    {
        return [
            ["Inactive"],
            ["Active"],
            ["Banned"],
            ["Suspended"],
            ["Accepted"],
            ["Published"],
            ["Draft"],
            [0],
            [1],
            [null],
        ];
    }

    /**
     * Test setState() with invalid state.
     *
     * @expectedException Thytanium\Database\Exceptions\InvalidStateException
     * @return void
     */
    public function test_set_invalid_state()
    {
        $model = new StatefulModel;

        $model->setState('Suspended');
    }

    /**
     * Test the default null state is not the same as inactive.
     * 
     * @return void
     */
    public function test_null_is_not_inactive()
    {
        $model = new TestModel(['state_id' => null]);

        $this->assertFalse($model->isState('Inactive'));
    }
}
