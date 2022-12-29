<?php
/*
 * Copyright (C) 2021-2022 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceCpuStatisticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cpu_usage_percentage' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
