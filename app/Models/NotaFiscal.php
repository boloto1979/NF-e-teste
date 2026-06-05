<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ItemNotaFiscal;
use App\Models\Duplicata;

class NotaFiscal extends Model
{
    protected $table = 'notas_fiscais';

    protected $fillable = [
        'chave_acesso',
        'numero',
        'serie',
        'data_emissao',
        'valor_total',
        'emitente_cnpj',
        'emitente_razao_social',
        'emitente_nome_fantasia',
        'emitente_ie',
        'emitente_logradouro',
        'emitente_numero',
        'emitente_bairro',
        'emitente_municipio',
        'emitente_uf',
        'emitente_cep',
        'emitente_fone',
        'destinatario_cnpj_cpf',
        'destinatario_razao_social',
        'destinatario_ie',
        'destinatario_logradouro',
        'destinatario_numero',
        'destinatario_bairro',
        'destinatario_municipio',
        'destinatario_uf',
        'destinatario_cep',
        'destinatario_fone',
        'natureza_operacao',
        'tipo_nf',
        'xml_path',
    ];

    protected $casts = [
        'data_emissao' => 'datetime',
        'valor_total'  => 'decimal:2',
    ];

    public function itens(): HasMany
    {
        return $this->hasMany(ItemNotaFiscal::class, 'nota_fiscal_id');
    }

    public function duplicatas(): HasMany
    {
        return $this->hasMany(Duplicata::class, 'nota_fiscal_id');
    }
}
