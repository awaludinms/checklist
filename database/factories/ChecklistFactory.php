<?php

namespace Database\Factories;

use App\Models\Checklist;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChecklistFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Checklist::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'object_domain' => $this->faker->name,
            'object_id' => $this->faker->randomNumber(),
            'due' => date('Y-m-d', strtotime($this->faker->iso8601())),
            'urgency' => $this->faker->numberBetween($min=1, $max=10),
            'description' => '',
            'task_id' => $this->faker->numberBetween($min=100, $max=500),
            'created_by' => 1,
        ];
    }
}
