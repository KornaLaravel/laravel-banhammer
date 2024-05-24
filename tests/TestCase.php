<?php

namespace Mchev\Banhammer\Tests;

use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->publishAndRunMigrations();
    }

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

    protected function publishAndRunMigrations(): void
    {
        // Publish the package migrations
        $this->artisan('vendor:publish', [
            '--provider' => 'Mchev\Banhammer\BanhammerServiceProvider',
            '--tag' => 'banhammer-migrations',
            '--force' => true,
        ])->run();

        // Run the migrations
        $this->artisan('migrate', ['--database' => 'testing'])->run();
    }

}
