<?php

namespace Database\Factories;

use App\Models\Cooperativa;
use Illuminate\Database\Eloquent\Factories\Factory;

class CooperativaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cooperativa::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nro_nim' => $this->faker->randomNumber(7),
            'nit' => $this->faker->randomNumber(8),
            'razon_social' => $this->faker->company,
        ];
    }
}
