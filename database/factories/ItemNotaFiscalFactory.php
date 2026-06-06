<?php

namespace Database\Factories;

use App\Models\ItemNotaFiscal;
use App\Models\NotaFiscal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItemNotaFiscal>
 */
class ItemNotaFiscalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantidade    = $this->faker->randomFloat(4, 1, 100);
        $valorUnitario = $this->faker->randomFloat(4, 1, 500);

        return [
            'nota_fiscal_id'       => NotaFiscal::factory(),
            'numero_item'          => $this->faker->numberBetween(1, 99),
            'codigo_produto'       => $this->faker->bothify('PROD-####'),
            'descricao'            => strtoupper($this->faker->words(4, true)),
            'ncm'                  => $this->faker->numerify('########'),
            'cfop'                 => $this->faker->randomElement(['5102', '6102', '5405', '6403']),
            'unidade_comercial'    => $this->faker->randomElement(['UN', 'PC', 'CX', 'KG', 'MT']),
            'quantidade'           => $quantidade,
            'valor_unitario'       => $valorUnitario,
            'valor_produto'        => round($quantidade * $valorUnitario, 2),
            'valor_desconto'       => $this->faker->randomFloat(2, 0, 50),
            'valor_frete'          => $this->faker->randomFloat(2, 0, 30),
            'valor_seguro'         => $this->faker->randomFloat(2, 0, 10),
            'valor_outros'         => 0,
            'valor_total_tributos' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
