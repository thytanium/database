<?php

namespace Tests\Unit\Eloquent\Traits;

use Tests\Unit\Eloquent\Models\TestModel;
use Thytanium\Tests\DatabaseMigrations;
use Thytanium\Tests\TestCase;

class DropdownableTest extends TestCase
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

        $this->fillDb();
    }

    /**
     * Fill db with test models.
     * 
     * @return void
     */
    protected function fillDb()
    {
        TestModel::create(['name' => 'model-1', 'email' => 'email@example.com']);
        TestModel::create(['name' => 'model-2', 'email' => 'email2@example.com']);
    }

    /**
     * Test most basic case.
     * 
     * @return void
     */
    public function test_basic_case()
    {
        $result = TestModel::dropdown();

        $this->assertEquals([
            1 => 'model-1',
            2 => 'model-2',
        ], $result->toArray());
    }

    /**
     * Test with different column names.
     * 
     * @return void
     */
    public function test_with_other_columns()
    {
        $result = TestModel::dropdown('email', 'name');

        $this->assertEquals([
            'email@example.com' => 'model-1',
            'email2@example.com' => 'model-2',
        ], $result->toArray());

        $result = TestModel::dropdown('name', 'email');

        $this->assertEquals([
            'model-1' => 'email@example.com',
            'model-2' => 'email2@example.com',
        ], $result->toArray());
    }

    /**
     * Test with callables as column names.
     * 
     * @return void
     */
    public function test_with_callables()
    {
        $result = TestModel::dropdown(function ($model) {
            return "{$model->id}.{$model->email}";
        }, 'name');

        $this->assertEquals([
            '1.email@example.com' => 'model-1',
            '2.email2@example.com' => 'model-2',
        ], $result->toArray());

        $result = TestModel::dropdown('id', function ($model) {
            return "{$model->email} : {$model->name}";
        });

        $this->assertEquals([
            1 => 'email@example.com : model-1',
            2 => 'email2@example.com : model-2',
        ], $result->toArray());
    }

    /**
     * Test with callables as column names with index.
     * 
     * @return void
     */
    public function test_callable_with_index()
    {
        $result = TestModel::dropdown(function ($model, $index) {
            return $index;
        });

        $this->assertEquals([
            0 => 'model-1',
            1 => 'model-2',
        ], $result->toArray());
    }

    /**
     * Test with dummy item.
     * 
     * @return void
     */
    public function test_with_dummy()
    {
        $result = TestModel::dropdown()->prepend('dummy');

        $this->assertEquals([
            '' => 'dummy',
            1 => 'model-1',
            2 => 'model-2',
        ], $result->toArray());
    }

    // public function test_with_translations()
    // {

    // }
}
