# API NF-e — Teste Prático Backend Laravel

API REST em Laravel para recebimento, processamento e consulta de Notas Fiscais Eletrônicas (NF-e) em formato XML.

---

## Requisitos

- Docker e Docker Compose

---

## Subindo o ambiente

```bash
# Clone o repositório
git clone https://github.com/boloto1979/NF-e-teste.git
cd NF-e-teste

# Copie o .env
cp .env.example .env

# Suba os containers (build + start)
docker compose up -d --build

# Gere a application key
docker compose exec app php artisan key:generate
```

---

## Rodando as Migrations

```bash
docker compose exec app php artisan migrate
```

---

## Rodando os Testes

```bash
docker compose exec app php artisan test
```

---

## Endpoints

### `POST /api/v1/notas-fiscais/upload`

Recebe um arquivo XML de NF-e via `multipart/form-data`.

**Campo:** `xml` (arquivo `.xml`, máximo 2MB)

**Exemplo com cURL:**

```bash
curl -X POST http://localhost:8000/api/v1/notas-fiscais/upload \
  -F "xml=@XMLs/NFe32260508228010000433550020001838391984678011.xml"
```

**Resposta de sucesso (201):**

```json
{
  "id": 1,
  "chave_acesso": "32260508228010000433550020001838391984678011",
  "numero": "183839",
  "serie": "2",
  "data_emissao": "2026-05-08T10:00:00-03:00",
  "valor_total": "2766.32",
  "natureza_operacao": "VENDA DE MERCADORIA",
  "tipo_nf": 1,
  "emitente": {
    "cnpj": "22801000043300",
    "razao_social": "EMPRESA EMITENTE LTDA"
  },
  "destinatario": {
    "cnpj_cpf": "10000000000191",
    "razao_social": "EMPRESA DESTINATARIA LTDA"
  },
  "itens": [],
  "duplicatas": []
}
```

**Erros possíveis:**

| HTTP | Motivo |
|------|--------|
| 422  | Campo `xml` ausente, tipo incorreto ou chave de acesso já cadastrada |
| 422  | XML corrompido ou sem a tag `<infNFe>` |

---

### `GET /api/v1/notas-fiscais/{id}/boletos`

Retorna os dados estruturados para emissão de boletos. O `{id}` pode ser o **ID numérico** ou a **chave de acesso (44 dígitos)**.

**Exemplo com cURL:**

```bash
# Por ID
curl http://localhost:8000/api/v1/notas-fiscais/1/boletos

# Por chave de acesso
curl http://localhost:8000/api/v1/notas-fiscais/32260508228010000433550020001838391984678011/boletos
```

**Resposta (200):**

```json
{
  "nota_fiscal_id": 1,
  "chave_acesso": "32260508228010000433550020001838391984678011",
  "cedente": {
    "cnpj": "22801000043300",
    "razao_social": "EMPRESA EMITENTE LTDA",
    "logradouro": "RUA EMITENTE",
    "numero": "100",
    "bairro": "CENTRO",
    "municipio": "SAO PAULO",
    "uf": "SP",
    "cep": "01001000"
  },
  "sacado": {
    "cnpj_cpf": "10000000000191",
    "razao_social": "EMPRESA DESTINATARIA LTDA",
    "logradouro": "AV DESTINATARIO",
    "numero": "200",
    "bairro": "BAIRRO",
    "municipio": "RIO DE JANEIRO",
    "uf": "RJ",
    "cep": "20000000"
  },
  "parcelas": [
    {
      "numero_duplicata": "001",
      "data_vencimento": "2026-06-20",
      "valor": "1383.16",
      "linha_digitavel": "22801.00005 00100.100006 00100.100006 1 202606201383160000"
    }
  ]
}
```

> **Nota:** o campo `linha_digitavel` é simulado/mockado para fins de demonstração.

---

## Estrutura do Banco

```
notas_fiscais
  ├── id
  ├── chave_acesso (unique, 44 chars)
  ├── numero, serie, data_emissao, valor_total
  ├── emitente_* (cnpj, razao_social, endereço...)
  └── destinatario_* (cnpj_cpf, razao_social, endereço...)

itens_nota_fiscal (N → 1 notas_fiscais)
  ├── nota_fiscal_id (FK)
  ├── numero_item, codigo_produto, descricao
  ├── ncm, cfop, unidade_comercial
  └── quantidade, valor_unitario, valor_produto, valor_desconto...

duplicatas (N → 1 notas_fiscais)
  ├── nota_fiscal_id (FK)
  ├── numero_duplicata
  ├── data_vencimento
  └── valor
```

---

## Serviços disponíveis

| Serviço  | URL                    |
|----------|------------------------|
| API      | http://localhost:8000  |
| pgAdmin  | http://localhost:8080  |
