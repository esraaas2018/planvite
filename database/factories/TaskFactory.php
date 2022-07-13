<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'=>$this->faker->name,
            'sprint_id'=>1,
            'user_id'=>2,
            'deadline'=>$this->faker->date,
            'status_id'=>'1',
            'description'=>$this->faker->sentence,
        ];
    }
}
