<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DeviceStatusSeeder::class,
            DeviceCommandErrorTypeSeeder::class,
            DeviceCommandStatusSeeder::class,
            DeviceJobErrorTypeSeeder::class,
            DeviceJobStatusSeeder::class,
        ]);
    }
}
