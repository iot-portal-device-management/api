<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceMemoryStatisticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'available_memory_in_bytes' => $this->faker->numberBetween($min = 107374182, $max = 34359738368),
        ];
    }
}
