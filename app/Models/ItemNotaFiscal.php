<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemNotaFiscal extends Model
{
    use HasFactory;
    protected $table = 'itens_nota_fiscal';

    protected $fillable = [
        'nota_fiscal_id',
        'numero_item',
        'codigo_produto',
        'descricao',
        'ncm',
        'cfop',
        'unidade_comercial',
        'quantidade',
        'valor_unitario',
        'valor_produto',
        'valor_desconto',
        'valor_frete',
        'valor_seguro',
        'valor_outros',
        'valor_total_tributos',
    ];

    protected $casts = [
        'quantidade'           => 'decimal:4',
        'valor_unitario'       => 'decimal:4',
        'valor_produto'        => 'decimal:2',
        'valor_desconto'       => 'decimal:2',
        'valor_frete'          => 'decimal:2',
        'valor_seguro'         => 'decimal:2',
        'valor_outros'         => 'decimal:2',
        'valor_total_tributos' => 'decimal:2',
    ];

    public function notaFiscal(): BelongsTo
    {
        return $this->belongsTo(NotaFiscal::class, 'nota_fiscal_id');
    }
}
