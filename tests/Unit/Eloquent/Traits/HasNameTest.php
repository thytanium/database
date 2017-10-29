<?php

namespace Tests\Unit\Eloquent\Traits;

use Illuminate\Database\Query\Builder;
use Tests\Unit\Eloquent\Models\TestModel;
use Thytanium\Database\Eloquent\HasName;
use Thytanium\Tests\DatabaseMigrations;
use Thytanium\Tests\TestCase;

class HasNameTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test name() returns a valid query.
     * 
     * @return void
     */
    public function test_valid_scope()
    {
        // Apply the query scope
        $query = TestModel::name('some_name')->getQuery();

        $this->assertInstanceOf(Builder::class, $query);
        $this->assertCount(1, $query->wheres);
        $this->assertArraySubset([
            'column' => 'name',
            'operator' => '=',
            'value' => 'some_name',
        ], $query->wheres[0]);

        TestModel::create(['name' => 'some_name']);
        $this->assertInstanceOf(TestModel::class, TestModel::findByName('some_name'));
        $this->assertNull(TestModel::findByName('some_other_name'));
    }
}
