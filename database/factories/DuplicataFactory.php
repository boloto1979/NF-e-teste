<?php

namespace Database\Factories;

use App\Models\Duplicata;
use App\Models\NotaFiscal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Duplicata>
 */
class DuplicataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nota_fiscal_id'   => NotaFiscal::factory(),
            'numero_duplicata' => $this->faker->numerify('###'),
            'data_vencimento'  => $this->faker->dateTimeBetween('now', '+6 months'),
            'valor'            => $this->faker->randomFloat(2, 100, 10000),
        ];
    }
}
