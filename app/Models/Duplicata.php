<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Duplicata extends Model
{
    protected $table = 'duplicatas';

    protected $fillable = [
        'nota_fiscal_id',
        'numero_duplicata',
        'data_vencimento',
        'valor',
    ];

    protected $casts = [
        'data_vencimento' => 'date',
        'valor'           => 'decimal:2',
    ];

    public function notaFiscal(): BelongsTo
    {
        return $this->belongsTo(NotaFiscal::class, 'nota_fiscal_id');
    }
}
