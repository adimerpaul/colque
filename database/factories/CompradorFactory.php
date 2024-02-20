<?php

namespace Database\Factories;

use App\Models\Comprador;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompradorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comprador::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nro_nim' => $this->faker->randomNumber(7),
            'nit' => $this->faker->randomNumber(9),
            'razon_social' => $this->faker->company,
        ];
    }
}
