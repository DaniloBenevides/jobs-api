<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => $this->faker->text("100"),
        ];
    }

    /**
     * Creates a regular Role
     *
     * @return static
     */
    public function regular()
    {
        return $this->state(function (array $attributes) {
            return [
                'id' => 1,
                'name' => "regular",
            ];
        });
    }

    /**
     * Creates a manager Role
     *
     * @return static
     */
    public function manager()
    {
        return $this->state(function (array $attributes) {
            return [
                'id' => 2,
                'name' => "manager",
            ];
        });
    }


}
