<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotaFiscalSeeder extends Seeder
{
    public function run(): void
    {
        // 5 notas com itens e duplicatas
        NotaFiscalFactory::new()
            ->count(5)
            ->comItens(3)
            ->comDuplicatas(2)
            ->create();

        // 3 notas sem duplicatas (pagamento à vista)
        NotaFiscalFactory::new()
            ->count(3)
            ->comItens(5)
            ->create();

        $this->command->info('Criadas 8 notas fiscais de teste com itens e duplicatas.');
    }
}
