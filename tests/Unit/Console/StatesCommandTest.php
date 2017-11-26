<?php

namespace Tests\Unit\Console;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use Mockery as m;
use Thytanium\Tests\TestCase;

class StatesCommandTest extends TestCase
{
    /**
     * Test default command flow.
     * 
     * @return void
     */
    public function test_default_flow()
    {
        $files = m::mock('Illuminate\Filesystem\Filesystem');
        $composer = m::mock('Illuminate\Support\Composer');
        $seeder = m::mock('Thytanium\Database\Seeders\StateSeeder[run]');
        $command = m::mock(
            'Thytanium\Database\Console\StatesCommand[migration]', 
            [$files, $composer, $seeder]
        )->shouldAllowMockingProtectedMethods();

        $command->shouldReceive('migration')
            ->once()
            ->withNoArgs();

        $seeder->shouldNotReceive('run');

        $this->app[Kernel::class]->registerCommand($command);

        $this->artisan('db:states');
    }

    /**
     * Test command with --seed option.
     * 
     * @return void
     */
    public function test_with_seed_option()
    {
        $files = m::mock('Illuminate\Filesystem\Filesystem[get,put]');
        $files->shouldNotReceive('get');
        $files->shouldNotReceive('put');

        $composer = m::mock('Illuminate\Support\Composer[dumpAutoloads]', [$files]);
        $composer->shouldNotReceive('dumpAutoloads');

        $seeder = m::mock('Thytanium\Database\Seeders\StateSeeder[run]');
        $seeder->shouldReceive('run')->once()->withNoArgs();

        $command = m::mock(
            'Thytanium\Database\Console\StatesCommand[createBaseMigration]',
            [$files, $composer, $seeder]
        )->shouldAllowMockingProtectedMethods();
        $command->shouldNotReceive('createBaseMigration');

        $this->app[Kernel::class]->registerCommand($command);

        $this->artisan('db:states', ['--seed' => true]);
    }
}
