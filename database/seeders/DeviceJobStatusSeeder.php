<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace Database\Seeders;

use App\Models\DeviceJobStatus;
use Illuminate\Database\Seeder;

class DeviceJobStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeviceJobStatus::create([
            'name' => DeviceJobStatus::STATUS_PENDING,
        ]);

        DeviceJobStatus::create([
            'name' => DeviceJobStatus::STATUS_PROCESSING,
        ]);

        DeviceJobStatus::create([
            'name' => DeviceJobStatus::STATUS_SUCCESSFUL,
        ]);

        DeviceJobStatus::create([
            'name' => DeviceJobStatus::STATUS_FAILED,
        ]);
    }
}
