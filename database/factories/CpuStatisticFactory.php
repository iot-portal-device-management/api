<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CpuStatisticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'system_cpu_percentage' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
