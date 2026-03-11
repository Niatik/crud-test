<?php

namespace Database\Factories;

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence(rand(3, 6), false),
            'description' => $this->faker->optional(0.7)->paragraph(),
            'status'      => $this->faker->randomElement(TaskStatusEnum::cases())->value,
        ];
    }

    public function statusNew(): static
    {
        return $this->state(['status' => TaskStatusEnum::New->value]);
    }

    public function statusInProgress(): static
    {
        return $this->state(['status' => TaskStatusEnum::InProgress->value]);
    }

    public function statusDone(): static
    {
        return $this->state(['status' => TaskStatusEnum::Done->value]);
    }
}
