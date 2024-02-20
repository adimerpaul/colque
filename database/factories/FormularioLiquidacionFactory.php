<?php

namespace Database\Factories;

use App\Models\FormularioLiquidacion;
use App\Models\Producto;
use App\Patrones\Estado;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormularioLiquidacionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FormularioLiquidacion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fecha = $this->faker->dateTimeBetween('- 1 year', '+ 1 year');
        return [
            'sigla' => 'CM',
            'numero_lote' => $this->faker->unique()->numberBetween(1, 30),
            'letra' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', 'F']),
            'anio' => $fecha->format('Y'),
            'fecha_cotizacion' => $fecha,
            'fecha_pesaje' => $fecha,
            'fecha_liquidacion' => $fecha,
            'producto' => Producto::find(rand(1,4))->info,
            'tipo_cambio_id' => rand(1, 365),
            'peso_bruto' => 0,
            'tara' => 0,
            'merma' => 1,
            'peso_seco' => 0,
            'sacos' => 1,
            'cliente_id' => rand(1, 175),
            'estado' => Estado::EnProceso
        ];
    }
}
