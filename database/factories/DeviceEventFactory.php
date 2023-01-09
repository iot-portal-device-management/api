<?php
/*
 * Copyright (C) 2021-2023 Intel Corporation
 * SPDX-License-Identifier: MIT
 */

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'raw_data' => '{"app":"compose","cmd":"down","containerTag":"hjckhjkhjk"}',
        ];
    }
}
