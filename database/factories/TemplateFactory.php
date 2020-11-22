<?php

namespace Database\Factories;

use App\Models\Template;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Template::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $rand = Rand(1, 6);
        $unit = ['hour', 'minute'];

        $items = [];
        for ( $i=0; $i < $rand; $i++) {
            $items[] = [
                'description' => 'description ' . $rand,
                'urgency' => $this->faker->numberBetween($min=1, $max=10),
                'due_interval' => $this->faker->numberBetween($min=1, $max=24),
                'due_unit' => $unit[Rand(0, 1)]
            ];
        }

        return [
            'name' => $this->faker->name,
            'checklist' => json_encode([
                'description' => '',
                'due_interval' => $this->faker->numberBetween($min=1, $max=24),
                'due_unit' => $unit[Rand(0, 1)],
            ]),
            'items' => json_encode($items)
        ];
    }
}
