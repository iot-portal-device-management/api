<?php

declare(strict_types=1);

namespace App\Facades;

use App\Services\Mqtt\ConnectionManager;
use Illuminate\Support\Facades\Facade;
use PhpMqtt\Client\Contracts\MqttClient;

/**
 * @method static MqttClient connection(string $name = null)
 * @method static void disconnect(string $connection = null)
 * @method static void publish(string $topic, string $message, bool $retain = false, string $connection = null)
 *
 */
class MQTT extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ConnectionManager::class;
    }
}
