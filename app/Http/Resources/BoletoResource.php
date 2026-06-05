<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BoletoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'nota_fiscal_id' => $this->id,
            'chave_acesso'   => $this->chave_acesso,
            'cedente' => [
                'cnpj'         => $this->emitente_cnpj,
                'razao_social' => $this->emitente_razao_social,
                'logradouro'   => $this->emitente_logradouro,
                'numero'       => $this->emitente_numero,
                'bairro'       => $this->emitente_bairro,
                'municipio'    => $this->emitente_municipio,
                'uf'           => $this->emitente_uf,
                'cep'          => $this->emitente_cep,
            ],
            'sacado' => [
                'cnpj_cpf'     => $this->destinatario_cnpj_cpf,
                'razao_social' => $this->destinatario_razao_social,
                'logradouro'   => $this->destinatario_logradouro,
                'numero'       => $this->destinatario_numero,
                'bairro'       => $this->destinatario_bairro,
                'municipio'    => $this->destinatario_municipio,
                'uf'           => $this->destinatario_uf,
                'cep'          => $this->destinatario_cep,
            ],
            'parcelas' => $this->whenLoaded('duplicatas', function () {
                return $this->duplicatas->map(fn ($dup) => [
                    'numero_duplicata' => $dup->numero_duplicata,
                    'data_vencimento'  => $dup->data_vencimento?->toDateString(),
                    'valor'            => $dup->valor,
                    'linha_digitavel'  => $this->gerarLinhaDigitavel($dup->numero_duplicata, $dup->data_vencimento?->toDateString(), $dup->valor),
                ]);
            }),
        ];
    }

    private function gerarLinhaDigitavel(string $numeroDup, ?string $vencimento, mixed $valor): string
    {
        $valorFormatado = str_pad((string) ((int) round((float) $valor * 100)), 10, '0', STR_PAD_LEFT);
        $vencFormatado  = $vencimento ? str_replace('-', '', $vencimento) : '00000000';
        $cnpj           = preg_replace('/\D/', '', $this->emitente_cnpj);

        return sprintf(
            '%s.%s %s.%s %s.%s %s %s%s',
            substr($cnpj, 0, 5),
            substr($cnpj, 5, 5),
            str_pad($numeroDup, 5, '0', STR_PAD_LEFT),
            str_pad($numeroDup, 6, '0', STR_PAD_LEFT),
            str_pad($numeroDup, 5, '0', STR_PAD_LEFT),
            str_pad($numeroDup, 6, '0', STR_PAD_LEFT),
            '1',
            $vencFormatado,
            $valorFormatado
        );
    }
}
