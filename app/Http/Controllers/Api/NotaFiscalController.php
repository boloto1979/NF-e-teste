<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadNotaFiscalRequest;
use App\Http\Resources\BoletoResource;
use App\Http\Resources\NotaFiscalResource;
use App\Models\NotaFiscal;
use App\Services\NfeParserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class NotaFiscalController extends Controller
{
    public function __construct(private readonly NfeParserService $parser) 
    {

    }

    public function upload(UploadNotaFiscalRequest $request): JsonResponse
    {
        try {
            $nota = $this->parser->import($request->file('xml'));
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Não foi possível processar o XML.',
                'errors'  => $e->errors(),
            ], 422);
        }

        return response()->json(new NotaFiscalResource($nota), 201);
    }

    public function boletos(string $id): JsonResponse
    {
        $nota = strlen($id) === 44
            ? NotaFiscal::with(['itens', 'duplicatas'])->where('chave_acesso', $id)->firstOrFail()
            : NotaFiscal::with(['itens', 'duplicatas'])->findOrFail($id);

        return response()->json(new BoletoResource($nota));
    }
}
