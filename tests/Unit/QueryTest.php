<?php

namespace Tests\Unit;

use Mockery as m;
use Tests\Unit\TestQuery;
use Thytanium\Tests\TestCase;

class QueryTest extends TestCase
{
    /**
     * Test default sort same as indicated in constructor.
     * 
     * @return void
     */
    public function test_correct_default_sort()
    {
        $builder = m::mock('Illuminate\Database\Query\Builder');
        $query = new TestQuery($builder, ['some_field', 'some_sort']);

        $this->assertEquals(['some_field', 'some_sort'], $query->defaultSort);
    }
}
