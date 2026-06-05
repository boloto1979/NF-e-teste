<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotaFiscalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                        => $this->id,
            'chave_acesso'              => $this->chave_acesso,
            'numero'                    => $this->numero,
            'serie'                     => $this->serie,
            'data_emissao'              => $this->data_emissao?->toIso8601String(),
            'valor_total'               => $this->valor_total,
            'natureza_operacao'         => $this->natureza_operacao,
            'tipo_nf'                   => $this->tipo_nf,
            'emitente' => [
                'cnpj'          => $this->emitente_cnpj,
                'razao_social'  => $this->emitente_razao_social,
                'nome_fantasia' => $this->emitente_nome_fantasia,
                'ie'            => $this->emitente_ie,
                'logradouro'    => $this->emitente_logradouro,
                'numero'        => $this->emitente_numero,
                'bairro'        => $this->emitente_bairro,
                'municipio'     => $this->emitente_municipio,
                'uf'            => $this->emitente_uf,
                'cep'           => $this->emitente_cep,
                'fone'          => $this->emitente_fone,
            ],
            'destinatario' => [
                'cnpj_cpf'     => $this->destinatario_cnpj_cpf,
                'razao_social' => $this->destinatario_razao_social,
                'ie'           => $this->destinatario_ie,
                'logradouro'   => $this->destinatario_logradouro,
                'numero'       => $this->destinatario_numero,
                'bairro'       => $this->destinatario_bairro,
                'municipio'    => $this->destinatario_municipio,
                'uf'           => $this->destinatario_uf,
                'cep'          => $this->destinatario_cep,
                'fone'         => $this->destinatario_fone,
            ],
            'itens'      => ItemNotaFiscalResource::collection($this->whenLoaded('itens')),
            'duplicatas' => DuplicataResource::collection($this->whenLoaded('duplicatas')),
            'xml_path'   => $this->xml_path,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
