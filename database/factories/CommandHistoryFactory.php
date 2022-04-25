<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommandHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
//            'type' => config('constants.mqtt_methods_integer_types'. '.' . array_rand(config('constants.mqtt_methods_integer_types'))),
            'payload' => '{"app":"compose","cmd":"down","containerTag":"hjckhjkhjk"}',
            'responded_at' => $this->faker->dateTime(),
        ];
    }
}
