<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notas_fiscais', function (Blueprint $table) {
            $table->id();
            $table->char('chave_acesso', 44)->unique();
            $table->string('numero', 9);
            $table->string('serie', 3);
            $table->dateTime('data_emissao');
            $table->decimal('valor_total', 15, 2);

            $table->string('emitente_cnpj', 14);
            $table->string('emitente_razao_social');
            $table->string('emitente_nome_fantasia')->nullable();
            $table->string('emitente_ie', 20)->nullable();
            $table->string('emitente_logradouro')->nullable();
            $table->string('emitente_numero', 20)->nullable();
            $table->string('emitente_bairro')->nullable();
            $table->string('emitente_municipio')->nullable();
            $table->string('emitente_uf', 2)->nullable();
            $table->string('emitente_cep', 8)->nullable();
            $table->string('emitente_fone', 20)->nullable();

            $table->string('destinatario_cnpj_cpf', 14);
            $table->string('destinatario_razao_social');
            $table->string('destinatario_ie', 20)->nullable();
            $table->string('destinatario_logradouro')->nullable();
            $table->string('destinatario_numero', 20)->nullable();
            $table->string('destinatario_bairro')->nullable();
            $table->string('destinatario_municipio')->nullable();
            $table->string('destinatario_uf', 2)->nullable();
            $table->string('destinatario_cep', 8)->nullable();
            $table->string('destinatario_fone', 20)->nullable();

            $table->string('natureza_operacao');
            $table->tinyInteger('tipo_nf')->comment('0=Entrada, 1=Saída');
            $table->string('xml_path')->nullable()->comment('Caminho do arquivo XML armazenado');

            $table->timestamps();

            $table->index('emitente_cnpj');
            $table->index('destinatario_cnpj_cpf');
            $table->index('data_emissao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas_fiscais');
    }
};
