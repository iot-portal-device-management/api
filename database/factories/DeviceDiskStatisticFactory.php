<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceDiskStatisticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'disk_usage_percentage' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
