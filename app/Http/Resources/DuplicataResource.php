<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DuplicataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'numero_duplicata' => $this->numero_duplicata,
            'data_vencimento'  => $this->data_vencimento?->toDateString(),
            'valor'            => $this->valor,
        ];
    }
}
