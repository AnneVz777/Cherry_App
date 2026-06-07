<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Laravel-12.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/REST-API-009688?style=for-the-badge&logo=fastapi&logoColor=white" alt="REST API">
  <img src="https://img.shields.io/badge/Status-Em%20Desenvolvimento-yellow?style=for-the-badge" alt="Status">
</p>

<h1 align="center">Cherry E-Commerce — API Backend</h1>

<p align="center">
  Backend de uma loja virtual desenvolvido com Laravel 12 e PHP 8.2.<br>
  API RESTful com rotas versionadas, padrão MVC, controle de estoque transacional e envio de e-mail automático.
</p>

---

## Índice

- [Sobre o Projeto](#-sobre-o-projeto)
- [Tecnologias](#-tecnologias)
- [Arquitetura](#-arquitetura)
- [Modelagem de Dados](#-modelagem-de-dados)
- [Regras de Negócio](#-regras-de-negócio)
- [Endpoints da API](#-endpoints-da-api)
- [Como Executar](#-como-executar)
- [Conceitos Aplicados](#-conceitos-aplicados)
- [Status do Projeto](#-status-do-projeto)
- [Equipe](#-equipe)

---

## Sobre o Projeto

O **Cherry E-Commerce** é um sistema backend para gerenciamento de uma loja virtual de roupas, desenvolvido como projeto prático da disciplina de **Desenvolvimento Backend**.

A API cobre o ciclo completo de um e-commerce:

- Cadastro de **usuários** com disparo automático de e-mail de boas-vindas
- Gerenciamento de **produtos** com controle de estoque em tempo real
- Criação de **pedidos** protegida por transação de banco de dados
- Rastreamento de **entregas** com código e status logístico
- Registro de **avaliações** de produtos por usuários

> **Disciplina:** Desenvolvimento Backend — Prof. João Martins, Msc.  
> **Equipe:** Nayelle Fonseca · Anne Vitória · Kaique Marques · Rillary Luize

---

## Tecnologias

| Tecnologia | Versão | Finalidade |
|------------|--------|------------|
| PHP | 8.2+ | Linguagem principal |
| Laravel | 12.0 | Framework MVC |
| MySQL | 8.0 | Banco de dados relacional |
| Eloquent ORM | — | Mapeamento objeto-relacional |
| Laravel Mail | — | Envio de e-mails transacionais |
| Mailtrap | — | Ambiente de teste de e-mail |
| Insomnia | — | Teste de requisições HTTP |

---

## Arquitetura

O projeto segue o padrão **MVC (Model-View-Controller)** com separação clara de responsabilidades:

```
Cliente (Insomnia / Frontend)
        │
        │  HTTP Request (JSON)
        ▼
┌───────────────────┐
│   routes/api.php  │  ← Prefixo /api/v1 
└────────┬──────────┘
         │
         ▼
┌───────────────────────────────────────────┐
│              Controllers                   │
│  UsuarioController  │  ProdutoController  │
│  PedidoController   │  EntregaController  │
│         AvaliacaoController               │
└────────┬──────────────────────────────────┘
         │
         ▼
┌───────────────────────────────────────────┐
│           Models (Eloquent ORM)            │
│  Usuario · Produto · Pedido               │
│  ItemPedido · Entrega · Avaliacoes        │
└────────┬──────────────────────────────────┘
         │
         ▼
┌───────────────────────────────────────────┐
│           Banco de Dados MySQL             │
│  usuarios · produtos · pedidos            │
│  item_pedidos · entregas · avaliacoes     │
└───────────────────────────────────────────┘
```

### Estrutura de Diretórios

```
app/
├── Http/
│   └── Controllers/
│       ├── AvaliacaoController.php
│       ├── EntregaController.php
│       ├── PedidoController.php
│       ├── ProdutoController.php
│       └── UsuarioController.php
├── Mail/
│   └── CadastroConfirmado.php
└── Models/
    ├── Avaliacoes.php
    ├── Entrega.php
    ├── ItemPedido.php
    ├── Pedido.php
    ├── Produto.php
    └── Usuario.php

database/
└── migrations/
    ├── 2026_05_02_154038_create_produtos_table.php
    ├── 2026_05_02_154047_create_usuarios_table.php
    ├── 2026_05_02_154055_create_pedidos_table.php
    ├── 2026_05_02_154426_create_pedido_produto_table.php
    ├── 2026_05_02_173633_create_avaliacoes_table.php
    └── 2026_05_02_180000_create_entregas_table.php

routes/
└── api.php
```

---

## Modelagem de Dados

### Diagrama de Relacionamentos

```
┌─────────────┐        ┌──────────────────┐        ┌──────────────┐
│  usuarios   │        │     pedidos      │        │  item_pedidos│
│─────────────│        │──────────────────│        │──────────────│
│ id (PK)     │1      N│ id (PK)          │1      N│ id (PK)      │
│ nome        ├────────┤ user_id (FK)     ├────────┤ pedido_id(FK)│
│ email       │        │ status           │        │ produto_id(FK│
│ senha       │        │ status_pagamento  │        │ quantidade   │
│ telefone    │        │ metodo_pagamento  │        │ preco        │
└─────────────┘        │ total            │        └──────┬───────┘
                       │ data_pedido      │               │N
                       │ codigo_rastreio  │               │
                       │ data_entrega     │        ┌──────┴───────┐
                       └────────┬─────────┘        │   produtos   │
                                │1                 │──────────────│
                                │                  │ id (PK)      │
                       ┌────────┴─────────┐        │ nome         │
                       │    entregas      │        │ descricao    │
                       │──────────────────│        │ preco        │
                       │ id (PK)          │        │ estoque      │
                       │ pedido_id (FK)   │        │ imagem       │
                       │ status           │        └──────┬───────┘
                       │ codigo_rastreio  │               │1
                       │ data_envio       │               │
                       │ data_entrega     │        ┌──────┴───────┐
                       └──────────────────┘        │  avaliacoes  │
                                                   │──────────────│
                                                   │ id (PK)      │
                                                   │ user_id (FK) │
                                                   │ produto_id(FK│
                                                   │ nota         │
                                                   │ comentario   │
                                                   └──────────────┘
```

### Relacionamentos

| Entidade | Tipo | Entidade |
|----------|------|----------|
| Usuario | 1 : N | Pedidos |
| Pedido | 1 : N | ItensPedido |
| Pedido | 1 : 1 | Entrega |
| Produto | N : N | Pedidos (via `item_pedidos`) |
| Produto | 1 : N | Avaliacoes |
| Usuario | 1 : N | Avaliacoes |

---

## Regras de Negócio

### Usuários
- O campo `email` deve ser único no sistema
- Ao criar um usuário, um **e-mail de confirmação é disparado automaticamente** via Laravel Mailable

### Produtos
- O campo `estoque` não pode ser negativo
- O estoque é **decrementado automaticamente** ao criar um pedido

### Pedidos
- A criação de um pedido é executada dentro de uma **transação de banco de dados**
- Antes de criar cada item, o sistema **verifica se há estoque suficiente**
- Se qualquer etapa falhar, a transação é revertida com `DB::rollBack()` e retorna `400`
- O campo `total` é calculado e salvo automaticamente com base nos itens
- Ao finalizar, o status é atualizado para `confirmado` e o pagamento para `pago`

### Itens do Pedido
- O `preco` do produto é registrado **no momento da compra**, preservando o histórico mesmo que o preço mude depois

### Avaliações
- Vinculadas a um produto e a um usuário
- A nota deve estar entre **1 e 5**

---

## Endpoints da API

**Base URL:** `http://127.0.0.1:8000/api/v1`  
**Formato:** Todas as requisições e respostas utilizam `application/json`  
---

### Usuários `/usuarios`

| Método | Endpoint | Descrição | Status |
|--------|----------|-----------|--------|
| `GET` | `/usuarios` | Lista todos os usuários | `200` |
| `GET` | `/usuarios/{id}` | Retorna um usuário pelo ID | `200` / `404` |
| `POST` | `/usuarios` | Cria usuário e envia e-mail | `201` |
| `PUT` | `/usuarios/{id}` | Atualiza dados do usuário | `200` / `404` |
| `DELETE` | `/usuarios/{id}` | Remove um usuário | `200` / `404` |

<details>
<summary><strong>Exemplo de requisição — POST /usuarios</strong></summary>

```json
{
  "nome": "Ana Silva",
  "email": "ana@teste.com",
  "senha": "123456",
  "telefone": "81999999999"
}
```

**Resposta `201 Created`:**
```json
{
  "id": 1,
  "nome": "Ana Silva",
  "email": "ana@teste.com",
  "telefone": "81999999999",
  "created_at": "2026-07-02T10:00:00Z"
}
```
</details>

---

### Produtos `/produtos`

| Método | Endpoint | Descrição | Status |
|--------|----------|-----------|--------|
| `GET` | `/produtos` | Lista todos os produtos | `200` |
| `GET` | `/produtos/{id}` | Retorna um produto pelo ID | `200` / `404` |
| `POST` | `/produtos` | Cria um novo produto | `201` |
| `PUT` | `/produtos/{id}` | Atualiza um produto | `200` / `404` |
| `DELETE` | `/produtos/{id}` | Remove um produto | `200` / `404` |

<details>
<summary><strong>Exemplo de requisição — POST /produtos</strong></summary>

```json
{
  "nome": "Camiseta Básica",
  "descricao": "Camiseta 100% algodão",
  "preco": 49.90,
  "estoque": 100
}
```
</details>

---

### Pedidos `/pedidos`

| Método | Endpoint | Descrição | Status |
|--------|----------|-----------|--------|
| `GET` | `/pedidos` | Lista pedidos com itens e usuário | `200` |
| `GET` | `/pedidos/{id}` | Retorna pedido completo pelo ID | `200` / `404` |
| `POST` | `/pedidos` | Cria pedido com controle de estoque | `201` / `400` |
| `PUT` | `/pedidos/{id}` | Atualiza um pedido | `200` / `404` |
| `DELETE` | `/pedidos/{id}` | Remove um pedido | `200` / `404` |
| `PUT` | `/pedidos/{id}/entrega` | Atualiza rastreio e status de entrega | `200` |
| `PUT` | `/pedidos/{id}/avaliacao` | Registra avaliação do pedido | `200` |

<details>
<summary><strong>Exemplo de requisição — POST /pedidos</strong></summary>

```json
{
  "user_id": 1,
  "metodo_pagamento": "cartao",
  "data_entrega_prevista": "2026-07-20",
  "itens": [
    { "produto_id": 1, "quantidade": 2 },
    { "produto_id": 3, "quantidade": 1 }
  ]
}
```

**Resposta `201 Created`:**
```json
{
  "id": 1,
  "status": "confirmado",
  "status_pagamento": "pago",
  "total": 149.70,
  "usuario": { "id": 1, "nome": "Ana Silva" },
  "itens": [
    { "produto_id": 1, "quantidade": 2, "preco": 49.90 },
    { "produto_id": 3, "quantidade": 1, "preco": 49.90 }
  ]
}
```

**Resposta `400 Bad Request` (estoque insuficiente):**
```json
{
  "erro": true,
  "mensagem": "Estoque insuficiente para o produto Camiseta Básica"
}
```
</details>

<details>
<summary><strong>Exemplo de requisição — PUT /pedidos/{id}/entrega</strong></summary>

```json
{
  "status_entrega": "em_transito",
  "codigo_rastreio": "BR123456789",
  "data_envio": "2026-07-10",
  "data_entrega_prevista": "2026-07-15"
}
```
</details>

---

### Entregas `/entregas`

| Método | Endpoint | Descrição | Status |
|--------|----------|-----------|--------|
| `GET` | `/entregas` | Lista todas as entregas | `200` |
| `GET` | `/entregas/{id}` | Retorna uma entrega pelo ID | `200` / `404` |
| `POST` | `/entregas` | Cria um registro de entrega | `201` |
| `PUT` | `/entregas/{id}` | Atualiza uma entrega | `200` / `404` |
| `DELETE` | `/entregas/{id}` | Remove uma entrega | `200` / `404` |

<details>
<summary><strong>Exemplo de requisição — POST /entregas</strong></summary>

```json
{
  "pedido_id": 1,
  "status": "pendente",
  "endereco_entrega": "Rua das Flores, 123, Recife - PE"
}
```
</details>

---

### Avaliações `/avaliacoes`

| Método | Endpoint | Descrição | Status |
|--------|----------|-----------|--------|
| `GET` | `/avaliacoes` | Lista todas as avaliações | `200` |
| `GET` | `/avaliacoes/{id}` | Retorna uma avaliação pelo ID | `200` / `404` |
| `POST` | `/avaliacoes` | Cria uma avaliação | `201` |
| `PUT` | `/avaliacoes/{id}` | Atualiza uma avaliação | `200` / `404` |
| `DELETE` | `/avaliacoes/{id}` | Remove uma avaliação | `200` / `404` |

<details>
<summary><strong>Exemplo de requisição — POST /avaliacoes</strong></summary>

```json
{
  "user_id": 1,
  "produto_id": 1,
  "nota": 5,
  "comentario": "Produto incrível, recomendo!"
}
```
</details>

---

## Como Executar

### Pré-requisitos

- PHP 8.2+
- Composer
- MySQL (via XAMPP ou outro)
- Conta no [Mailtrap](https://mailtrap.io) para teste de e-mails


### Banco de Dados e Servidor

```bash
# Rodar as migrations
php artisan migrate

# Iniciar o servidor
php artisan serve

# Verificar todas as rotas registradas
php artisan route:list
```

A API estará disponível em `http://127.0.0.1:8000/api/v1`

---

## Conceitos Aplicados

| Aula | Conceito | Aplicação no Projeto |
|------|----------|----------------------|
| Aula 1 | O que é Backend | API como camada servidora — processa regras, persiste dados, provê serviços |
| Aula 2 | Modelagem de Dados | MER/DER, 3 Formas Normais, tabela associativa `item_pedidos`, FKs com `CASCADE` |
| Aula 3 | Protocolo HTTP | Verbos corretos (GET/POST/PUT/DELETE), status codes semânticos, respostas em JSON |
| Aula 4 | Rotas no Laravel | Prefixo `/v1`, substantivos no plural, agrupamento com `prefix()` |
| Aula 5 | Controllers no MVC | Thin Controllers — apenas orquestram, sem lógica de negócio inline |
| Aula 6 | Models e Services | Active Record com Eloquent, `$fillable`, relacionamentos, Eager Loading com `with()` |

---

## Status do Projeto

| Funcionalidade | Status |
|----------------|--------|
| Migrations e modelagem do banco | ✅ Concluído |
| Rotas REST versionadas (`/v1`) | ✅ Concluído |
| CRUD de Usuários | ✅ Concluído |
| CRUD de Produtos | ✅ Concluído |
| CRUD de Pedidos | ✅ Concluído |
| CRUD de Entregas | ✅ Concluído |
| CRUD de Avaliações | ✅ Concluído |
| E-mail automático no cadastro (Mailable) | ✅ Concluído |
| Transação de banco na criação de pedidos | ✅ Concluído |
| Controle de estoque automático | ✅ Concluído |

---

<p align="center">
  <sub><em>"Programar é resolver problemas hoje; projetar software é evitar que eles voltem amanhã."</em></sub>
</p>
