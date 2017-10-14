<?php

namespace Tests\Unit\Eloquent;

use Illuminate\Database\Query\Builder;
use Thytanium\Tests\DatabaseMigrations;
use Thytanium\Tests\TestCase;

class HasEmailTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test email() returns a valid query.
     * 
     * @return void
     */
    public function test_valid_scope()
    {
        // Apply the query scope
        $query = TestModel::email('some_email')->getQuery();

        $this->assertInstanceOf(Builder::class, $query);
        $this->assertCount(1, $query->wheres);
        $this->assertArraySubset([
            'column' => 'email',
            'operator' => '=',
            'value' => 'some_email',
        ], $query->wheres[0]);

        TestModel::create(['email' => 'some_email']);
        $this->assertInstanceOf(TestModel::class, TestModel::findByEmail('some_email'));
        $this->assertNull(TestModel::findByEmail('some_other_email'));
    }
}
