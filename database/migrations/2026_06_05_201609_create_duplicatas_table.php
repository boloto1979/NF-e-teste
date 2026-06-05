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
        Schema::create('duplicatas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_fiscal_id')
                ->constrained('notas_fiscais')
                ->cascadeOnDelete();

            $table->string('numero_duplicata', 60);
            $table->date('data_vencimento');
            $table->decimal('valor', 15, 2);

            $table->timestamps();

            $table->index('nota_fiscal_id');
            $table->index('data_vencimento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('duplicatas');
    }
};
