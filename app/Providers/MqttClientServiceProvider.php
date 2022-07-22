<?php

namespace App\Providers;

use App\Services\Mqtt\ConnectionManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use PhpMqtt\Client\Contracts\Repository;
use PhpMqtt\Client\Repositories\MemoryRepository;

/**
 * Registers the MQTT client within the application.
 *
 */
class MqttClientServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerServices();
    }

    /**
     * Registers the services offered by this package.
     *
     * @return void
     */
    protected function registerServices(): void
    {
        $this->app->bind(ConnectionManager::class, function (Application $app) {
            return new ConnectionManager($app, $app->make('config')->get('mqtt-client', []));
        });

        $this->app->bind(Repository::class, MemoryRepository::class);
    }
}
