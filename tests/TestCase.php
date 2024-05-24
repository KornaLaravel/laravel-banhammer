<?php

namespace Mchev\Banhammer\Tests;

use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{

    use RefreshDatabase;

    protected function getEnvironmentSetUp($app): void
    {
        // Load the .env file
        $app->useEnvironmentPath(__DIR__.'/..');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);
        parent::getEnvironmentSetUp($app);
    }

    protected function getPackageProviders($app): array
    {
        return [
            'Mchev\Banhammer\BanhammerServiceProvider',
        ];
    }

    /**
     * Run package database migrations.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(database_path('migrations'));
    }

}