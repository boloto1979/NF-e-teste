<?php

namespace App\Services;

use App\Models\Duplicata;
use App\Models\ItemNotaFiscal;
use App\Models\NotaFiscal;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use SimpleXMLElement;

class NfeParserService
{
    private const NS = 'http://www.portalfiscal.inf.br/nfe';

    public function import(UploadedFile $file): NotaFiscal
    {
        $xml = $this->parseXml($file);

        $infNFe = $this->findInfNFe($xml);

        $chaveAcesso = $this->extractChaveAcesso($infNFe);

        if (NotaFiscal::where('chave_acesso', $chaveAcesso)->exists()) {
            throw ValidationException::withMessages([
                'xml' => "A nota fiscal com chave de acesso {$chaveAcesso} já está cadastrada.",
            ]);
        }

        $xmlPath = $this->storeXml($file, $chaveAcesso);

        return DB::transaction(function () use ($infNFe, $chaveAcesso, $xmlPath) {
            $data = $this->extractNotaData($infNFe, $chaveAcesso);
            $data['xml_path'] = $xmlPath;

            $nota = NotaFiscal::create($data);

            $this->importItens($nota, $infNFe);
            $this->importDuplicatas($nota, $infNFe);

            return $nota->load(['itens', 'duplicatas']);
        });
    }

    private function parseXml(UploadedFile $file): SimpleXMLElement
    {
        $content = file_get_contents($file->getRealPath());

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content, SimpleXMLElement::class, LIBXML_NOERROR);

        if ($xml === false) {
            $errors = array_map(fn ($e) => $e->message, libxml_get_errors());
            libxml_clear_errors();
            throw ValidationException::withMessages([
                'xml' => 'XML inválido ou corrompido: ' . implode('; ', $errors),
            ]);
        }

        return $xml;
    }

    private function findInfNFe(SimpleXMLElement $xml): SimpleXMLElement
    {
        $xml->registerXPathNamespace('nfe', self::NS);

        $result = $xml->xpath('//nfe:infNFe');

        if (empty($result)) {
            throw ValidationException::withMessages([
                'xml' => 'Estrutura de NF-e inválida: tag <infNFe> não encontrada.',
            ]);
        }

        return $result[0];
    }

    private function extractChaveAcesso(SimpleXMLElement $infNFe): string
    {
        $id = (string) $infNFe->attributes()['Id'];

        return ltrim($id, 'NFe');
    }

    private function extractNotaData(SimpleXMLElement $infNFe, string $chaveAcesso): array
    {
        $ide   = $infNFe->ide;
        $emit  = $infNFe->emit;
        $dest  = $infNFe->dest;

        $enderEmit = $emit->enderEmit ?? null;
        $enderDest = $dest->enderDest ?? null;

        return [
            'chave_acesso'              => $chaveAcesso,
            'numero'                    => (string) $ide->nNF,
            'serie'                     => (string) $ide->serie,
            'data_emissao'              => (string) $ide->dhEmi,
            'valor_total'               => (float) $infNFe->total->ICMSTot->vNF,
            'natureza_operacao'         => (string) $ide->natOp,
            'tipo_nf'                   => (int) $ide->tpNF,

            'emitente_cnpj'             => (string) $emit->CNPJ,
            'emitente_razao_social'     => (string) $emit->xNome,
            'emitente_nome_fantasia'    => (string) ($emit->xFant ?? ''),
            'emitente_ie'               => (string) ($emit->IE ?? ''),
            'emitente_logradouro'       => $enderEmit ? (string) $enderEmit->xLgr : null,
            'emitente_numero'           => $enderEmit ? (string) $enderEmit->nro : null,
            'emitente_bairro'           => $enderEmit ? (string) $enderEmit->xBairro : null,
            'emitente_municipio'        => $enderEmit ? (string) $enderEmit->xMun : null,
            'emitente_uf'               => $enderEmit ? (string) $enderEmit->UF : null,
            'emitente_cep'              => $enderEmit ? (string) $enderEmit->CEP : null,
            'emitente_fone'             => $enderEmit ? (string) $enderEmit->fone : null,

            'destinatario_cnpj_cpf'     => (string) ($dest->CNPJ ?? $dest->CPF ?? ''),
            'destinatario_razao_social' => (string) $dest->xNome,
            'destinatario_ie'           => (string) ($dest->IE ?? ''),
            'destinatario_logradouro'   => $enderDest ? (string) $enderDest->xLgr : null,
            'destinatario_numero'       => $enderDest ? (string) $enderDest->nro : null,
            'destinatario_bairro'       => $enderDest ? (string) $enderDest->xBairro : null,
            'destinatario_municipio'    => $enderDest ? (string) $enderDest->xMun : null,
            'destinatario_uf'           => $enderDest ? (string) $enderDest->UF : null,
            'destinatario_cep'          => $enderDest ? (string) $enderDest->CEP : null,
            'destinatario_fone'         => $enderDest ? (string) $enderDest->fone : null,
        ];
    }

    private function importItens(NotaFiscal $nota, SimpleXMLElement $infNFe): void
    {
        foreach ($infNFe->det as $det) {
            $prod    = $det->prod;
            $imposto = $det->imposto ?? null;

            ItemNotaFiscal::create([
                'nota_fiscal_id'       => $nota->id,
                'numero_item'          => (int) $det->attributes()['nItem'],
                'codigo_produto'       => (string) $prod->cProd,
                'descricao'            => (string) $prod->xProd,
                'ncm'                  => (string) $prod->NCM,
                'cfop'                 => (string) $prod->CFOP,
                'unidade_comercial'    => (string) $prod->uCom,
                'quantidade'           => (float) $prod->qCom,
                'valor_unitario'       => (float) $prod->vUnCom,
                'valor_produto'        => (float) $prod->vProd,
                'valor_desconto'       => (float) ($prod->vDesc ?? 0),
                'valor_frete'          => (float) ($prod->vFrete ?? 0),
                'valor_seguro'         => (float) ($prod->vSeg ?? 0),
                'valor_outros'         => (float) ($prod->vOutro ?? 0),
                'valor_total_tributos' => $imposto ? (float) ($imposto->vTotTrib ?? 0) : null,
            ]);
        }
    }

    private function importDuplicatas(NotaFiscal $nota, SimpleXMLElement $infNFe): void
    {
        $cobr = $infNFe->cobr ?? null;

        if ($cobr === null || !isset($cobr->dup)) {
            return;
        }

        foreach ($cobr->dup as $dup) {
            Duplicata::create([
                'nota_fiscal_id'   => $nota->id,
                'numero_duplicata' => (string) $dup->nDup,
                'data_vencimento'  => (string) $dup->dVenc,
                'valor'            => (float) $dup->vDup,
            ]);
        }
    }

    private function storeXml(UploadedFile $file, string $chaveAcesso): string
    {
        $filename = "NFe{$chaveAcesso}.xml";

        return $file->storeAs('xmls', $filename, 'local');
    }
}
