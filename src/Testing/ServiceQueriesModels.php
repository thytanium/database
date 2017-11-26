<?php

namespace Thytanium\Database\Testing;

use Mockery as m;

trait ServiceQueriesModels
{
    /**
     * Build service with mocked newQuery().
     *
     * @param  string $builder
     * @param  string $modelClassName
     * @return mixed
     */
    protected function buildServiceWithMockedQuery($builder, $modelClassName)
    {
        $service = m::mock($this->serviceClass.'[newQuery]')
            ->shouldAllowMockingProtectedMethods();

        $service->shouldReceive('newQuery')
            ->once()
            ->with($modelClassName)
            ->andReturn($builder);

        return $service;
    }

    /**
     * Mock model Eloquent builder.
     *
     * @param  string $className    Model class name
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function mockQueryBuilder($className)
    {
        $connection = (new $className)->getConnection();
        $builder = m::mock('Illuminate\Database\Query\Builder', [
            $connection,
            $connection->getQueryGrammar(),
            $connection->getPostProcessor()
        ]);

        return m::mock('Illuminate\Database\Eloquent\Builder', [$builder]);
    }
}
