<?php

namespace Thytanium\Database\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Thytanium\Database\Seeders\StateSeeder;

class StatesCommand extends Command
{
    public $signature = 'db:states {--migration} {--seed}';

    public $description = 'Creates migration for states table. Seeds states table.';

    protected $composer;
    protected $files;
    protected $seeder;

    /**
     * New StatesCommand instance.
     * 
     * @param Composer   $composer
     * @param Filesystem $files
     */
    public function __construct(Composer $composer, Filesystem $files, StateSeeder $seeder)
    {
        parent::__construct();

        $this->composer = $composer;
        $this->files = $files;
        $this->seeder = $seeder;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // If migration option is enabled
        if ($this->option('migration')) {
            $this->migration();
        }

        // If seed option is enabled
        if ($this->option('seed')) {
            $this->seed();
        }

        // If no options enabled
        if ($this->option('migration') === false && $this->option('seed') === false) {
            $this->migration();
            $this->seed();
        }
    }

    /**
     * Create database migration.
     * 
     * @return void
     */
    protected function migration()
    {
        $fullPath = $this->createBaseMigration();

        $this->files->put($fullPath, $this->files->get(realpath(__DIR__.'/../../database/migrations/states.php')));

        $this->info('Migration created successfully!');

        $this->composer->dumpAutoloads();
    }

    /**
     * Seed default states.
     * 
     * @return void
     */
    protected function seed()
    {
        $this->seeder->run();

        $this->info('States seeded successfully!');
    }

    /**
     * Create a base migration file for the session.
     *
     * @return string
     */
    protected function createBaseMigration()
    {
        $name = 'create_states_table';

        $path = $this->laravel->databasePath().'/migrations';

        return $this->laravel['migration.creator']->create($name, $path);
    }
}
