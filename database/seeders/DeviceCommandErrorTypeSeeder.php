<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace Database\Seeders;

use App\Models\DeviceCommandErrorType;
use Illuminate\Database\Seeder;

class DeviceCommandErrorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeviceCommandErrorType::create([
            'name' => DeviceCommandErrorType::TYPE_MQTT_BROKER_CONNECTION_REFUSED,
            'error_code' => 'device_command.mqtt_broker_connection_refused',
            'description' => 'No connection could be made because the MQTT broker actively refused it',
        ]);

        DeviceCommandErrorType::create([
            'name' => DeviceCommandErrorType::TYPE_DEVICE_COMMAND_TYPE_NOT_SUPPORTED,
            'error_code' => 'device_command.device_command_type_not_supported',
            'description' => 'The device command type is not supported by the device',
        ]);

        DeviceCommandErrorType::create([
            'name' => DeviceCommandErrorType::TYPE_DEVICE_TIMEOUT,
            'error_code' => 'device_command.device_timeout',
            'description' => 'Timeout waiting for device to respond',
        ]);

        DeviceCommandErrorType::create([
            'name' => DeviceCommandErrorType::TYPE_OTHERS,
            'error_code' => 'device_command.others',
            'description' => 'Other error has occurred',
        ]);
    }
}
