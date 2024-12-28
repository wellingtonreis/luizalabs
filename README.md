# Desafio Técnico Luizalabs - Sistema de Gestão de Conta Corrente

## Contexto
Você está desenvolvendo um sistema de gestão de conta corrente para um banco. O sistema deve suportar uma série de operações e regras complexas para gerenciar as contas e transações dos clientes.

---

## Requisitos

### 1. Modelagem de Dados
- Crie um modelo de dados para representar uma conta corrente. Cada conta deve ter:
  - Número único
  - Saldo
  - Data de criação
  - Lista de transações
- As transações devem incluir:
  - Tipo (depósito, saque, transferência)
  - Valor
  - Data
  - Descrição opcional

### 2. Regras de Negócio
- **Limite de Crédito**: 
  - As contas podem ter um limite de crédito que deve ser respeitado durante as transações. 
  - O saldo disponível deve considerar o limite de crédito.
- **Taxas e Tarifas**: 
  - As retiradas e transferências podem incorrer em taxas que devem ser aplicadas automaticamente.
- **Transferências**:
  - Devem ser atômicas, garantindo que o saldo seja debitado da conta de origem e creditado na conta de destino em uma única operação.

### 3. Processamento de Transações
- Implemente um método para processar uma lista de transações em lote.
- Cada transação deve ser validada e aplicada ao saldo da conta conforme as regras de negócio.
- Caso uma transação falhe (ex.: saldo insuficiente):
  - Ela deve ser registrada.
  - A operação completa deve ser revertida para manter a integridade dos dados.

### 4. Concorrência e Desempenho
- O sistema deve suportar múltiplas transações simultâneas em uma conta.
- Implemente mecanismos de bloqueio ou sincronização para evitar inconsistências durante operações concorrentes.

### 5. Auditoria e Log
- Mantenha um log detalhado de todas as transações e alterações no saldo.
- O log deve ser consultável e permitir auditorias futuras.

---

## Entrega

### 1. Código-Fonte
- Forneça o código-fonte para:
  - Modelo de dados
  - Lógica de transações
  - Métodos de processamento
  - Endpoints (API REST).

### 2. Casos de Teste
- Crie testes unitários e de integração abrangentes para validar:
  - Funcionalidades
  - Regras de negócio

---

## Desafio Adicional
### Recuperação de Falhas
Proponha uma estratégia para recuperação de falhas em casos onde o sistema possa falhar durante o processamento de uma transação. 

**Como garantir que o sistema possa se recuperar sem perda de dados ou inconsistências?**

- Utilize abordagens como:
  - **Logs de Transações**: 
    - Registre todas as operações em um log transacional.
    - Permita replays para restaurar o estado do sistema.
  - **Banco de Dados com Suporte a Transações**: 
    - Garanta operações ACID (Atomicidade, Consistência, Isolamento, Durabilidade).
  - **Mecanismos de Retry**: 
    - Configure políticas de retry para transações falhadas.
  - **Snapshots de Estado**:
    - Periodicamente, salve o estado atual das contas para recuperação em caso de falhas críticas.

---

## Como Executar

1. Clone este repositório:
   ```bash
   git clone <url-do-repositorio>
   ```
2. Configure o ambiente:
   - Renomeie `.env.example` para `.env` e configure as credenciais do banco de dados.
3. Inicie docker:
   ```bash
   docker-compose up -d --build
   ```
4. Execute as migrações:
   ```bash
   make migrate
   ```
5. Gere os dados:
   ```bash
   make seed
   ```
6. Teste as funcionalidades com os endpoints disponíveis em api/.
7. Execute os teste de unidade:
   ```bash
   make test
   ```
8. Execute analise do código:
   ```bash
   make phpstan
   ```

9. Passos principais na seguinte ordem de execução:
   ```bash
   $ docker-compose up -d --build

   $ docker exec -it laravel-app bash

   $ make migrate

   $ make seed
   ```

10. Execute os endpoints em api/api_rabbitmq.http para simular a carga de transações:
- `Envia mensagens para fila`:
  - http://localhost:8000/api/v1/send-transfer-message-to-rabbitmq

- `Consome as mensagens da fila`:
  - http://localhost:8000/api/v1/consume-transfer-messages
---

11. Visualize as mensagens no rabbitmq:
  - http://localhost:15672/

## Estrutura de Diretórios
- `app/Models`:
  - Modelos para Conta e Transações
- `app/Src`:
  - Regras de negócio e lógica de transações
- `app/Http/Controllers`:
  - Endpoints para as operações de conta e transações
- `tests/`:
  - Casos de teste unitários e de integração

---

## Tecnologias Utilizadas
- Laravel 11
- PHP 8.2
- MySQL/PostgreSQL
- PHPUnit
- Docker

---

## Autor
Desenvolvido por Wellington Reis.
