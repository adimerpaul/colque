<?php

namespace Database\Factories;

use App\Models\Vehiculo;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehiculoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vehiculo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'placa' => $this->faker->unique()->numberBetween(1000, 5200) .$this->faker->randomLetter .$this->faker->randomLetter .$this->faker->randomLetter,
            'marca' => $this->faker->randomElement(['Nissan', 'Toyota', 'Suzuki', 'Honda']),
            'color' => $this->faker->randomElement(['Azul', 'Rojo', 'Negro', 'Blanco'])
        ];
    }
}
