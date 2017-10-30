<?php

namespace Tests\Unit\Console;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\Concerns\InteractsWithConsole;
use Mockery as m;
use Thytanium\Tests\TestCase;

class StatesCommandTest extends TestCase
{
    use InteractsWithConsole;

    /**
     * Test default command flow.
     * 
     * @return void
     */
    public function test_default_flow()
    {
        $files = m::mock('Illuminate\Filesystem\Filesystem[get,put]');
        $files->shouldReceive('get')
            ->once()
            ->with(realpath(__DIR__.'/../../../database/migrations/states.php'))
            ->andReturn('migration_contents');
        $files->shouldReceive('put')
            ->once()
            ->with('output_path', 'migration_contents');

        $composer = m::mock('Illuminate\Support\Composer[dumpAutoloads]', [$files]);
        $composer->shouldReceive('dumpAutoloads')->once();

        $seeder = m::mock('Thytanium\Database\Seeders\StateSeeder[run]');
        $seeder->shouldReceive('run')
            ->once()
            ->withNoArgs();

        $command = m::mock(
            'Thytanium\Database\Console\StatesCommand[createBaseMigration]',
            [$composer, $files, $seeder]
        )->shouldAllowMockingProtectedMethods();
        $command->shouldReceive('createBaseMigration')
            ->once()
            ->andReturn('output_path');

        $this->app[Kernel::class]->registerCommand($command);

        $this->artisan('db:states');
    }

    /**
     * Test command with --migration option.
     * 
     * @return void
     */
    public function test_with_migration_option()
    {
        $files = m::mock('Illuminate\Filesystem\Filesystem[get,put]');
        $files->shouldReceive('get')
            ->once()
            ->with(realpath(__DIR__.'/../../../database/migrations/states.php'))
            ->andReturn('migration_contents');
        $files->shouldReceive('put')
            ->once()
            ->with('output_path', 'migration_contents');

        $composer = m::mock('Illuminate\Support\Composer[dumpAutoloads]', [$files]);
        $composer->shouldReceive('dumpAutoloads')->once();

        $seeder = m::mock('Thytanium\Database\Seeders\StateSeeder[run]');
        $seeder->shouldNotReceive('run');

        $command = m::mock(
            'Thytanium\Database\Console\StatesCommand[createBaseMigration]',
            [$composer, $files, $seeder]
        )->shouldAllowMockingProtectedMethods();
        $command->shouldReceive('createBaseMigration')
            ->once()
            ->andReturn('output_path');

        $this->app[Kernel::class]->registerCommand($command);

        $this->artisan('db:states', ['--migration' => true]);
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
            [$composer, $files, $seeder]
        )->shouldAllowMockingProtectedMethods();
        $command->shouldNotReceive('createBaseMigration');

        $this->app[Kernel::class]->registerCommand($command);

        $this->artisan('db:states', ['--seed' => true]);
    }
}
