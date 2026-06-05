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
        Schema::create('itens_nota_fiscal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_fiscal_id')
                ->constrained('notas_fiscais')
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('numero_item');
            $table->string('codigo_produto', 60)->nullable();
            $table->string('descricao');
            $table->string('ncm', 8)->nullable();
            $table->string('cfop', 4)->nullable();
            $table->string('unidade_comercial', 6)->nullable();
            $table->decimal('quantidade', 15, 4);
            $table->decimal('valor_unitario', 15, 4);
            $table->decimal('valor_produto', 15, 2);
            $table->decimal('valor_desconto', 15, 2)->default(0);
            $table->decimal('valor_frete', 15, 2)->default(0);
            $table->decimal('valor_seguro', 15, 2)->default(0);
            $table->decimal('valor_outros', 15, 2)->default(0);
            $table->decimal('valor_total_tributos', 15, 2)->nullable();

            $table->timestamps();

            $table->index('nota_fiscal_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens_nota_fiscal');
    }
};
