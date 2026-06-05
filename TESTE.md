
Teste Prático de Seleção: Desenvolvedor Backend Laravel (API NF-e & Financeiro)

Prazo de entrega 05/06/2026 até as 23:59


Visão geral do Desafio
Sua missão é construir uma API em Laravel que automatize o recebimento de Notas Fiscais Eletrônicas (NF-e) de compra (XML). A aplicação deverá processar esse arquivo, armazenar os dados de forma estruturada em um banco de dados relacional (MySQL ou PostgreSQL) e expor endpoints para que o setor financeiro consulte esses dados para a geração de boletos de pagamento.

Requisitos Técnicos e Funcionais

1. Migrations e Modelagem de Dados
O banco de dados deve ser relacional (MySQL ou PostgreSQL). Você deve criar as Migrations e os Models do Eloquent estruturando as seguintes informações extraídas do XML:
Nota Fiscal: Chave de acesso (string de 44 caracteres, única), número da nota, série, data de emissão, valor total, CNPJ/Razão Social do Emitente e CNPJ/CPF do Destinatário, Itens e outras informações que vierem neste XMLs

Parcelas/Duplicatas: Uma nota fiscal pode ter uma ou mais parcelas de pagamento. Deve haver um relacionamento de 1:N (Uma Nota possui muitas Parcelas) contendo: número da parcela, data de vencimento e valor da parcela.

2. Endpoints da API
 POST /api/v1/notas-fiscais/upload Deve receber um arquivo XML via form-data (campo xml).
Validação (Form Request): Garantir que o arquivo é um XML e que não tem tamanho excessivo.
Service/Parsing: Ler o XML da NF-e e identificar as tags que correspondem aos dados de cada item do mesmo.

Regra de Negócio: Se a chave de acesso já existir no banco, deve retornar um erro HTTP 422 Unprocessable Entity.
Retorno: Código HTTP 201 Created com os dados da nota salvos em formato JSON.

 GET /api/v1/notas-fiscais/{id}/boletos Deve buscar a nota fiscal pelo ID (ou pela Chave de Acesso) e retornar os dados estruturados para que um serviço externo possa emitir os boletos.

Retorno: Um JSON estruturado contendo os dados do Sacado (Destinatário), Cedente (Emitente), e uma lista com as parcelas prontas para faturamento (contendo data de vencimento, valor e um campo mockado/simulado com a linha digitável do boleto).

O Que Será Avaliado (Critérios de Correção)
Uso do Ecossistema Laravel: Uso correto de Form Requests para validação, Eloquent ORM para relacionamentos, Migrations bem estruturadas (com chaves estrangeiras e índices apropriados), e API Resources para formatar o retorno JSON.

Organização de Código: Código limpo (Clean Code). O controller não deve conter a lógica de leitura do XML; utilize Services ou Actions para isolar essa responsabilidade.

Tratamento de Exceções: Como a API se comporta se o XML vier corrompido ou sem a tag de cobrança/duplicatas.

Testes Automatizados: Criação de pelo menos um teste de integração (Feature Test) simulando o upload do XML e validando o comportamento no banco de dados.


Instruções de Entrega
Código Fonte: Subir para um repositório (GitHub/GitLab).

Ambiente (Docker): O projeto deve subir preferencialmente usando Laravel Sail ou um arquivo docker-compose.yml próprio que configure a aplicação e o banco de dados (MySQL ou PostgreSQL).


Arquivo README.md: Deve conter o passo a passo para rodar as migrations, subir o ambiente e um exemplo de payload/XML para teste.
Seeders/Factories (Opcional, mas ganha pontos): Disponibilizar dados de teste automatizados.

Sessão de Apresentação: O projeto deverá ser apresentado ao vivo, durante uma videochamada que será agendada após confirmação de entrega,que terá o prazo máximo de até o dia 05/06/2026

Arquivos de Xmls para ajudar seguem no link em anexo a este item XMLs



Boa sorte.
