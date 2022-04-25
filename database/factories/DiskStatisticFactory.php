<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DiskStatisticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'disk_percentage_used' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
