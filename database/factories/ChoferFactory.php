<?php

namespace Database\Factories;

use App\Models\Chofer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChoferFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Chofer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'licencia' => $this->faker->creditCardNumber,
            'nombre' => $this->faker->name,
            'celular' => $this->faker->numberBetween(60000000,79999999),
        ];
    }
}
