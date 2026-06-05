<?php

use App\Http\Controllers\Api\NotaFiscalController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/notas-fiscais/upload', [NotaFiscalController::class, 'upload']);
    Route::get('/notas-fiscais/{id}/boletos', [NotaFiscalController::class, 'boletos']);
});
