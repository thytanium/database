<?php

namespace Tests\Unit\Eloquent\Traits;

use Illuminate\Database\Query\Builder;
use Tests\Unit\Eloquent\TestModel;
use Thytanium\Database\Eloquent\Models\State;
use Thytanium\Tests\TestCase;

class HasStateTest extends TestCase
{
    /**
     * Test inactive() scope.
     * 
     * @return void
     */
    public function test_inactive_scope()
    {
        $query = TestModel::inactive()->getQuery();

        $this->assertInstanceOf(Builder::class, $query);
        $this->assertCount(1, $query->wheres);
        $this->assertArraySubset([
            'column' => 'state_id',
            'operator' => '=',
            'value' => State::$inactive,
        ], $query->wheres[0]);
    }

    /**
     * Test active() scope.
     * 
     * @return void
     */
    public function test_active_scope()
    {
        $query = TestModel::active()->getQuery();

        $this->assertInstanceOf(Builder::class, $query);
        $this->assertCount(1, $query->wheres);
        $this->assertArraySubset([
            'column' => 'state_id',
            'operator' => '=',
            'value' => State::$active,
        ], $query->wheres[0]);
    }

    /**
     * Test banned() scope.
     * 
     * @return void
     */
    public function test_banned_scope()
    {
        $query = TestModel::banned()->getQuery();

        $this->assertInstanceOf(Builder::class, $query);
        $this->assertCount(1, $query->wheres);
        $this->assertArraySubset([
            'column' => 'state_id',
            'operator' => '=',
            'value' => State::$banned,
        ], $query->wheres[0]);
    }

    /**
     * Test suspended scope.
     * 
     * @return void
     */
    public function test_suspended_scope()
    {
        $query = TestModel::suspended()->getQuery();

        $this->assertInstanceOf(Builder::class, $query);
        $this->assertCount(1, $query->wheres);
        $this->assertArraySubset([
            'column' => 'state_id',
            'operator' => '=',
            'value' => State::$suspended,
        ], $query->wheres[0]);
    }

    /**
     * Test accepted() scope.
     * 
     * @return void
     */
    public function test_accepted_scope()
    {
        $query = TestModel::accepted()->getQuery();

        $this->assertInstanceOf(Builder::class, $query);
        $this->assertCount(1, $query->wheres);
        $this->assertArraySubset([
            'column' => 'state_id',
            'operator' => '=',
            'value' => State::$accepted,
        ], $query->wheres[0]);
    }
}
