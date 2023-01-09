<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace Database\Seeders;

use App\Models\DeviceCommandStatus;
use Illuminate\Database\Seeder;

class DeviceCommandStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeviceCommandStatus::create([
            'name' => DeviceCommandStatus::STATUS_PENDING,
        ]);

        DeviceCommandStatus::create([
            'name' => DeviceCommandStatus::STATUS_PROCESSING,
        ]);

        DeviceCommandStatus::create([
            'name' => DeviceCommandStatus::STATUS_SUCCESSFUL,
        ]);

        DeviceCommandStatus::create([
            'name' => DeviceCommandStatus::STATUS_FAILED,
        ]);
    }
}
