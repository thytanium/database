<?php

namespace Thytanium\Database;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Thytanium\Database\Console\StatesCommand;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Boot this service provider.
     * 
     * @return void
     */
    public function boot()
    {
        $this->commands([
            StatesCommand::class,
        ]);
    }
}
