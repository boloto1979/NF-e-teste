<?php

namespace Tests\Feature;

use App\Models\NotaFiscal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class NotaFiscalUploadTest extends TestCase
{
    use RefreshDatabase;

    private function xmlComDuplicatas(): string
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<nfeProc versao="4.00" xmlns="http://www.portalfiscal.inf.br/nfe">
  <NFe xmlns="http://www.portalfiscal.inf.br/nfe">
    <infNFe Id="NFe32260508228010000433550020001838391984678011" versao="4.00">
      <ide>
        <cUF>32</cUF><cNF>19846780</cNF>
        <natOp>VENDA DE MERCADORIA</natOp>
        <mod>55</mod><serie>2</serie><nNF>183839</nNF>
        <dhEmi>2026-05-08T10:00:00-03:00</dhEmi>
        <tpNF>1</tpNF>
      </ide>
      <emit>
        <CNPJ>22801000043300</CNPJ>
        <xNome>EMPRESA TESTE LTDA</xNome>
        <xFant>TESTE</xFant>
        <enderEmit>
          <xLgr>RUA TESTE</xLgr><nro>100</nro><xBairro>CENTRO</xBairro>
          <xMun>SAO PAULO</xMun><UF>SP</UF><CEP>01001000</CEP>
        </enderEmit>
        <IE>123456789</IE><CRT>3</CRT>
      </emit>
      <dest>
        <CNPJ>10000000000191</CNPJ>
        <xNome>CLIENTE TESTE LTDA</xNome>
        <enderDest>
          <xLgr>AV CLIENTE</xLgr><nro>200</nro><xBairro>BAIRRO</xBairro>
          <xMun>RIO DE JANEIRO</xMun><UF>RJ</UF><CEP>20000000</CEP>
        </enderDest>
        <indIEDest>1</indIEDest>
      </dest>
      <det nItem="1">
        <prod>
          <cProd>001</cProd><xProd>PRODUTO TESTE</xProd>
          <NCM>12345678</NCM><CFOP>6102</CFOP>
          <uCom>UN</uCom><qCom>10.0000</qCom>
          <vUnCom>276.6320</vUnCom><vProd>2766.32</vProd>
        </prod>
        <imposto><vTotTrib>100.00</vTotTrib></imposto>
      </det>
      <total>
        <ICMSTot>
          <vNF>2766.32</vNF>
        </ICMSTot>
      </total>
      <cobr>
        <dup><nDup>001</nDup><dVenc>2026-06-20</dVenc><vDup>1383.16</vDup></dup>
        <dup><nDup>002</nDup><dVenc>2026-07-20</dVenc><vDup>1383.16</vDup></dup>
      </cobr>
    </infNFe>
  </NFe>
</nfeProc>
XML;
    }

    private function xmlSemDuplicatas(): string
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<nfeProc versao="4.00" xmlns="http://www.portalfiscal.inf.br/nfe">
  <NFe xmlns="http://www.portalfiscal.inf.br/nfe">
    <infNFe Id="NFe42260517979609000157550010007244271263500382" versao="4.00">
      <ide>
        <cUF>42</cUF><cNF>12635003</cNF>
        <natOp>VENDA DE MERCADORIA</natOp>
        <mod>55</mod><serie>1</serie><nNF>724427</nNF>
        <dhEmi>2026-05-17T09:00:00-03:00</dhEmi>
        <tpNF>1</tpNF>
      </ide>
      <emit>
        <CNPJ>79960900015500</CNPJ>
        <xNome>EMITENTE SEM DUP LTDA</xNome>
        <enderEmit>
          <xLgr>RUA SEM DUP</xLgr><nro>1</nro><xBairro>CENTRO</xBairro>
          <xMun>CURITIBA</xMun><UF>PR</UF><CEP>80000000</CEP>
        </enderEmit>
        <IE>987654321</IE><CRT>3</CRT>
      </emit>
      <dest>
        <CNPJ>20000000000172</CNPJ>
        <xNome>DESTINATARIO SEM DUP</xNome>
        <enderDest>
          <xLgr>AV DEST</xLgr><nro>50</nro><xBairro>BAIRRO</xBairro>
          <xMun>FLORIANOPOLIS</xMun><UF>SC</UF><CEP>88000000</CEP>
        </enderDest>
        <indIEDest>1</indIEDest>
      </dest>
      <det nItem="1">
        <prod>
          <cProd>002</cProd><xProd>PRODUTO SEM DUP</xProd>
          <NCM>87654321</NCM><CFOP>6102</CFOP>
          <uCom>UN</uCom><qCom>1.0000</qCom>
          <vUnCom>500.00</vUnCom><vProd>500.00</vProd>
        </prod>
        <imposto><vTotTrib>20.00</vTotTrib></imposto>
      </det>
      <total>
        <ICMSTot><vNF>500.00</vNF></ICMSTot>
      </total>
    </infNFe>
  </NFe>
</nfeProc>
XML;
    }

    private function uploadXml(string $conteudo, string $nome = 'nota.xml'): \Illuminate\Testing\TestResponse
    {
        $arquivo = UploadedFile::fake()->createWithContent($nome, $conteudo);

        return $this->postJson('/api/v1/notas-fiscais/upload', ['xml' => $arquivo]);
    }

    public function test_upload_cria_nota_fiscal_com_duplicatas(): void
    {
        $response = $this->uploadXml($this->xmlComDuplicatas());

        $response->assertStatus(201)
            ->assertJsonPath('chave_acesso', '32260508228010000433550020001838391984678011')
            ->assertJsonPath('numero', '183839')
            ->assertJsonPath('emitente.cnpj', '22801000043300');

        $this->assertDatabaseHas('notas_fiscais', [
            'chave_acesso' => '32260508228010000433550020001838391984678011',
            'numero'       => '183839',
        ]);

        $this->assertDatabaseCount('duplicatas', 2);
        $this->assertDatabaseCount('itens_nota_fiscal', 1);
    }

    public function test_upload_cria_nota_fiscal_sem_duplicatas(): void
    {
        $response = $this->uploadXml($this->xmlSemDuplicatas());

        $response->assertStatus(201);

        $this->assertDatabaseHas('notas_fiscais', [
            'chave_acesso' => '42260517979609000157550010007244271263500382',
        ]);

        $this->assertDatabaseCount('duplicatas', 0);
    }

    public function test_upload_rejeita_chave_duplicada(): void
    {
        $this->uploadXml($this->xmlComDuplicatas());
        $response = $this->uploadXml($this->xmlComDuplicatas());

        $response->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);
    }

    public function test_upload_rejeita_arquivo_sem_xml(): void
    {
        $arquivo = UploadedFile::fake()->create('nota.pdf', 100, 'application/pdf');

        $response = $this->postJson('/api/v1/notas-fiscais/upload', ['xml' => $arquivo]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['xml']);
    }

    public function test_upload_rejeita_requisicao_sem_arquivo(): void
    {
        $response = $this->postJson('/api/v1/notas-fiscais/upload', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['xml']);
    }

    public function test_upload_rejeita_xml_corrompido(): void
    {
        $response = $this->uploadXml('isso nao e um xml valido <<>>');

        $response->assertStatus(422);
    }

    public function test_boletos_retorna_estrutura_correta(): void
    {
        $this->uploadXml($this->xmlComDuplicatas());

        $nota = NotaFiscal::first();

        $this->getJson("/api/v1/notas-fiscais/{$nota->id}/boletos")
            ->assertStatus(200)
            ->assertJsonStructure([
                'nota_fiscal_id',
                'chave_acesso',
                'cedente' => ['cnpj', 'razao_social'],
                'sacado'  => ['cnpj_cpf', 'razao_social'],
                'parcelas' => [
                    '*' => ['numero_duplicata', 'data_vencimento', 'valor', 'linha_digitavel'],
                ],
            ]);
    }

    public function test_boletos_retorna_404_para_nota_inexistente(): void
    {
        $this->getJson('/api/v1/notas-fiscais/99999/boletos')
            ->assertStatus(404);
    }

    public function test_boletos_aceita_busca_por_chave_acesso(): void
    {
        $this->uploadXml($this->xmlComDuplicatas());

        $chave = '32260508228010000433550020001838391984678011';

        $this->getJson("/api/v1/notas-fiscais/{$chave}/boletos")
            ->assertStatus(200)
            ->assertJsonPath('chave_acesso', $chave);
    }
}
