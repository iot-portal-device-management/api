<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceCpuTemperatureStatisticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'temperature' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
