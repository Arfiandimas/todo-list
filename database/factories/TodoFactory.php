<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = ['pending', 'open', 'in_progress', 'completed'];
        $priority = ['low', 'medium', 'high'];

        return [
            'title' => $this->faker->sentence(3),
            'assignee' => $this->faker->randomElement(['Arfian', 'Dimas', 'Andi', 'Permana', 'Barjono']),
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'time_tracked' => $this->faker->randomFloat(2, 0, 10),
            'status' => $this->faker->randomElement($status),
            'priority' => $this->faker->randomElement($priority)
        ];
    }
}
