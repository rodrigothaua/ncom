# Guia de Atualização do Sistema

## 1. Preparação
Antes de executar as atualizações, certifique-se de:
- Fazer backup do banco de dados
- Estar em ambiente de desenvolvimento/homologação
- Ter permissões necessárias no banco de dados

## 2. Executar Migrações
Execute os seguintes comandos na ordem:

```bash
# Instalar dependências atualizadas
composer install

# Limpar caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Executar migrações do banco de dados
php artisan migrate

# Executar seeds iniciais (se necessário)
php artisan db:seed --class=OrcamentoSeeder
```

## 3. Migrar Dados Existentes
Execute os comandos de migração na seguinte ordem:

```bash
# Migrar dados de user tracking para processos
php artisan processos:migrar

# Migrar dados de orçamentos (se houver dados legados)
php artisan orcamentos:migrar
```

## 4. Verificações Pós-Migração

### 4.1 Verificar Estrutura do Banco
- Tabela `processos` deve ter colunas de user tracking (created_by, updated_by, deleted_by)
- Tabela `processos` deve ter suporte a soft deletes (deleted_at)
- Todas as chaves estrangeiras devem estar corretas

### 4.2 Verificar Dados
- Processos devem ter created_by preenchido
- Relações com usuários devem estar funcionando
- Soft deletes deve estar funcionando

### 4.3 Testar Funcionalidades
- Criar novo processo
- Editar processo existente
- Excluir processo (soft delete)
- Verificar rastreamento de usuários

## 5. Troubleshooting

### 5.1 Problemas Comuns
- **Erro de coluna não encontrada**: Execute `php artisan migrate:fresh` (atenção: isso apaga todos os dados)
- **Erro de chave estrangeira**: Verifique a ordem das migrações
- **Dados não migrados**: Execute novamente o comando específico de migração

### 5.2 Reverter Alterações
Em caso de problemas, você pode:
```bash
# Reverter última migração
php artisan migrate:rollback

# Reverter todas as migrações e executar novamente
php artisan migrate:refresh

# Reverter tudo e recriar com seeds
php artisan migrate:refresh --seed
```

## 6. Confirmação Final

### 6.1 Checklist de Validação
- [ ] Migrações executadas com sucesso
- [ ] Dados existentes migrados corretamente
- [ ] User tracking funcionando
- [ ] Soft deletes funcionando
- [ ] Relacionamentos íntegros
- [ ] Todas as funcionalidades testadas

### 6.2 Documentação
- Atualize a documentação do sistema se necessário
- Registre quaisquer problemas encontrados
- Documente soluções aplicadas

## 7. Contato
Em caso de problemas:
- Email: suporte@sigecom.com.br
- Documentação: docs.sigecom.com.br
