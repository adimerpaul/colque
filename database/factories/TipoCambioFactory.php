<?php

namespace Database\Factories;

use App\Models\TipoCambio;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoCambioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TipoCambio::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fecha' => $this->faker->unique()->dateTimeBetween('-1 year', '+ 1 year'),
            'dolar_compra' => 6.96,
            'dolar_venta' => 6.98
        ];
    }
}
