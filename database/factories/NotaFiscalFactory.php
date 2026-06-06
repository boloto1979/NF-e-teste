<?php

namespace Database\Factories;

use App\Models\NotaFiscal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NotaFiscal>
 */
class NotaFiscalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ufs = ['SP', 'RJ', 'MG', 'RS', 'PR', 'SC', 'BA', 'GO', 'PE', 'CE'];

        return [
            'chave_acesso'              => $this->faker->unique()->numerify(str_repeat('#', 44)),
            'numero'                    => $this->faker->numerify('#####'),
            'serie'                     => $this->faker->numerify('###'),
            'data_emissao'              => $this->faker->dateTimeBetween('-1 year', 'now'),
            'valor_total'               => $this->faker->randomFloat(2, 100, 50000),
            'natureza_operacao'         => $this->faker->randomElement([
                'VENDA DE MERCADORIA',
                'TRANSFERENCIA DE MERCADORIA',
                'DEVOLUCAO DE COMPRA',
            ]),
            'tipo_nf'                   => 1,

            'emitente_cnpj'             => $this->faker->numerify('##############'),
            'emitente_razao_social'     => strtoupper($this->faker->company()) . ' LTDA',
            'emitente_nome_fantasia'    => strtoupper($this->faker->company()),
            'emitente_ie'               => $this->faker->numerify('#########'),
            'emitente_logradouro'       => strtoupper($this->faker->streetName()),
            'emitente_numero'           => $this->faker->buildingNumber(),
            'emitente_bairro'           => strtoupper($this->faker->word()),
            'emitente_municipio'        => strtoupper($this->faker->city()),
            'emitente_uf'               => $this->faker->randomElement($ufs),
            'emitente_cep'              => $this->faker->numerify('########'),
            'emitente_fone'             => $this->faker->numerify('###########'),

            'destinatario_cnpj_cpf'     => $this->faker->numerify('##############'),
            'destinatario_razao_social' => strtoupper($this->faker->company()) . ' LTDA',
            'destinatario_ie'           => $this->faker->numerify('#########'),
            'destinatario_logradouro'   => strtoupper($this->faker->streetName()),
            'destinatario_numero'       => $this->faker->buildingNumber(),
            'destinatario_bairro'       => strtoupper($this->faker->word()),
            'destinatario_municipio'    => strtoupper($this->faker->city()),
            'destinatario_uf'           => $this->faker->randomElement($ufs),
            'destinatario_cep'          => $this->faker->numerify('########'),
            'destinatario_fone'         => $this->faker->numerify('###########'),

            'xml_path'                  => null,
        ];
    }

    public function comDuplicatas(int $quantidade = 2): static
    {
        return $this->afterCreating(function (NotaFiscal $nota) use ($quantidade) {
            DuplicataFactory::new()->count($quantidade)->create([
                'nota_fiscal_id' => $nota->id,
            ]);
        });
    }

    public function comItens(int $quantidade = 3): static
    {
        return $this->afterCreating(function (NotaFiscal $nota) use ($quantidade) {
            ItemNotaFiscalFactory::new()->count($quantidade)->create([
                'nota_fiscal_id' => $nota->id,
            ]);
        });
    }
}
