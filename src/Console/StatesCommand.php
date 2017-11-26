<?php

namespace Thytanium\Database\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Thytanium\Database\Seeders\StateSeeder;
use Thytanium\Migrations\Console\Command;

class StatesCommand extends Command
{
    public $signature = 'db:states {--seed}';

    public $description = 'Creates migration for states table. Seeds states table.';

    /**
     * @var StateSeeder
     */
    protected $seeder;

    /**
     * New StatesCommand instance.
     * 
     * @param Filesystem $files
     * @param Composer   $composer
     * @param StateSeeder $seeder
     */
    public function __construct(Filesystem $files, Composer $composer, StateSeeder $seeder)
    {
        parent::__construct($files, $composer);

        $this->seeder = $seeder;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // If seed option is NOT enabled
        if ($this->option('seed') === false) {
            $this->migration();
        } else {
            $this->seed();
        }
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
     * Get path where migration stub is located.
     *
     * @return  string
     */
    protected function stubPath()
    {
        return realpath(__DIR__.'/../../database/migrations/states.php');
    }

    /**
     * Get a name for this migration.
     *
     * @return  string
     */
    protected function migrationName()
    {
        return 'create_states_table';
    }
}
