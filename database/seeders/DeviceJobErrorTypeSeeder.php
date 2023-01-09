<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace Database\Seeders;

use App\Models\DeviceJobErrorType;
use Illuminate\Database\Seeder;

class DeviceJobErrorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeviceJobErrorType::create([
            'name' => DeviceJobErrorType::TYPE_MQTT_BROKER_CONNECTION_REFUSED,
            'error_code' => 'device_job.mqtt_broker_connection_refused',
            'description' => 'One or more devices has failed because the MQTT broker actively refused it',
        ]);

        DeviceJobErrorType::create([
            'name' => DeviceJobErrorType::TYPE_DEVICE_TIMEOUT,
            'error_code' => 'device_job.device_timeout',
            'description' => 'One or more devices has timed out while waiting for their response',
        ]);

        DeviceJobErrorType::create([
            'name' => DeviceJobErrorType::TYPE_OTHERS,
            'error_code' => 'device_job.others',
            'description' => 'Other error has occurred',
        ]);
    }
}
