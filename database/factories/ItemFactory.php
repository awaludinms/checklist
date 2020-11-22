<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Checklist;

use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'checklist_id' => Checklist::factory(),
            'due' => date('Y-m-d', strtotime($this->faker->iso8601())),
            'urgency' => $this->faker->numberBetween($min=1, $max=10),
            'description' => $this->faker->name,
            'assignee_id' => $this->faker->numberBetween($min=100, $max=200)
        ];
    }
}
