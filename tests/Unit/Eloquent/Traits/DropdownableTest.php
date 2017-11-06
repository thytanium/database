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
        TestModel::create(['name' => 'model-X', 'email' => 'email@example.com']);
        TestModel::create(['name' => 'model-Y', 'email' => 'email2@example.com']);
    }

    /**
     * Test dropdown() scope with basic params.
     * 
     * @return void
     */
    public function test_dropdown_scope()
    {
        $result = TestModel::dropdown();

        $this->assertEquals([
            1 => 'model-X',
            2 => 'model-Y',
        ], $result->toArray());
    }
}
