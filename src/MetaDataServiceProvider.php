<?php namespace DigitalRuby\MetaData;

use Illuminate\Support\ServiceProvider;

class MetaDataServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}