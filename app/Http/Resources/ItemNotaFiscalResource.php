<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemNotaFiscalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'numero_item'          => $this->numero_item,
            'codigo_produto'       => $this->codigo_produto,
            'descricao'            => $this->descricao,
            'ncm'                  => $this->ncm,
            'cfop'                 => $this->cfop,
            'unidade_comercial'    => $this->unidade_comercial,
            'quantidade'           => $this->quantidade,
            'valor_unitario'       => $this->valor_unitario,
            'valor_produto'        => $this->valor_produto,
            'valor_desconto'       => $this->valor_desconto,
            'valor_frete'          => $this->valor_frete,
            'valor_seguro'         => $this->valor_seguro,
            'valor_outros'         => $this->valor_outros,
            'valor_total_tributos' => $this->valor_total_tributos,
        ];
    }
}
