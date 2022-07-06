<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventHistoryFactory extends Factory
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
