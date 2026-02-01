<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Socio>
 */
class SocioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'numero_socio' => fake()->unique()->numberBetween(1000, 9999),
            'dni' => fake()->unique()->numerify('########'),
            'nombre' => fake()->firstName(),
            'apellido' => fake()->lastName(),
            'barrio_id' => 1, 
            'calle' => fake()->streetName(),
            'altura' => fake()->buildingNumber(),
            'fecha_nacimiento' => fake()->date(),
            'habilitado' => true,
        ];
    }
}
