# Módulo de Orçamentos - SIGECOM

## Visão Geral
O módulo de orçamentos permite o gerenciamento completo dos recursos financeiros, desde a fonte do orçamento até o pagamento final, passando por todas as etapas intermediárias como alocação, empenho e liquidação.

## Estrutura do Banco de Dados

### Tabelas Principais
1. `fontes_orcamento`
   - Armazena as fontes de recursos (Federal, Estadual, Municipal, etc.)
   - Controle de status ativo/inativo
   - Soft deletes habilitado

2. `categorias_despesa`
   - Classificação das despesas
   - Permite organizar as alocações por tipo
   - Soft deletes habilitado

3. `orcamentos`
   - Registro principal dos orçamentos
   - Vinculado a uma fonte
   - Controle de valores totais e utilizados
   - Soft deletes habilitado

4. `alocacoes_orcamento`
   - Distribuição dos recursos por categoria
   - Vinculação com processos
   - Controle de status (Planejado, Empenhado, etc.)
   - Soft deletes habilitado

5. `movimentacoes_orcamento`
   - Registro de todas as operações financeiras
   - Histórico completo de empenhos, liquidações e pagamentos
   - Soft deletes habilitado

## Funcionalidades

### 1. Gestão de Fontes
- Cadastro e manutenção de fontes de recurso
- Ativação/desativação de fontes
- Visualização de orçamentos vinculados
- Acompanhamento de valores por fonte

### 2. Gestão de Orçamentos
- Cadastro completo de orçamentos
- Vinculação com fonte de recurso
- Controle de datas e valores
- Suporte a emendas parlamentares
- Fluxo de aprovação (Em Análise → Aprovado/Reprovado)

### 3. Alocações
- Distribuição dos recursos por categoria
- Vinculação opcional com processos
- Controle de valores disponíveis
- Cancelamento de alocações

### 4. Movimentações Financeiras
- Empenho de valores
- Liquidação de despesas
- Pagamento de despesas
- Histórico completo de operações
- Controle de documentos (notas de empenho, etc.)

### 5. Relatórios
- Resumo por fonte de recurso
- Resumo por status
- Movimentações por período
- Alertas de vencimento

## Fluxo de Trabalho

1. **Fonte de Recurso**
   - Cadastrar fonte
   - Definir tipo e características

2. **Orçamento**
   - Criar orçamento
   - Vincular à fonte
   - Definir valores e datas
   - Submeter para aprovação

3. **Alocação**
   - Distribuir recursos por categoria
   - Vincular a processos (opcional)
   - Definir valores

4. **Execução**
   ```
   Alocação → Empenho → Liquidação → Pagamento
   ```

## Controles e Validações

1. **Valores**
   - Não permite alocar mais que o disponível
   - Controle por nível (orçamento → alocação → movimentação)
   - Validações em todas as operações financeiras

2. **Datas**
   - Controle de exercício financeiro
   - Validação de datas de início/fim
   - Alerta para vencimentos próximos

3. **Status**
   - Validação de sequência de operações
   - Controle de estados das alocações
   - Bloqueios baseados em status

4. **Usuários**
   - Rastreamento de todas as operações
   - Registro de responsáveis
   - Histórico de modificações

## Requisitos Técnicos

### Dependências
- Laravel Framework
- Bootstrap 5
- MySQL/MariaDB
- Bootstrap Icons

### Instalação
1. Executar migrations:
   ```bash
   php artisan migrate
   ```

2. Executar seeder inicial:
   ```bash
   php artisan db:seed --class=OrcamentoSeeder
   ```

3. Para migrar dados legados:
   ```bash
   php artisan orcamentos:migrar
   ```

### Manutenção
- Backup diário recomendado
- Monitor de integridade referencial
- Limpeza periódica de registros soft deleted

## APIs e Integrações

### Endpoints Disponíveis
1. Orçamentos
   - `GET /api/orcamentos` - Lista
   - `POST /api/orcamentos` - Criar
   - `GET /api/orcamentos/{id}` - Detalhes
   - `PUT /api/orcamentos/{id}` - Atualizar
   - `DELETE /api/orcamentos/{id}` - Excluir

2. Alocações
   - `GET /api/alocacoes` - Lista
   - `POST /api/alocacoes` - Criar
   - `GET /api/alocacoes/{id}` - Detalhes
   - `PUT /api/alocacoes/{id}` - Atualizar
   - `DELETE /api/alocacoes/{id}` - Excluir

### Formatos de Dados
- Entrada: JSON
- Saída: JSON
- Datas: ISO 8601
- Valores monetários: decimal(15,2)

## Suporte e Manutenção

### Logs
- Arquivos em `storage/logs/orcamentos/`
- Rotação diária
- Retenção de 30 dias

### Backups
- Dados em `storage/backups/orcamentos/`
- Rotina diária às 23h
- Retenção de 7 dias

### Contatos
- Suporte Técnico: suporte@sigecom.com.br
- Documentação: docs.sigecom.com.br/orcamentos
