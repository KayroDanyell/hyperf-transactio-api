
# Transaction API

Esta API desenvolvida no framework HyperF realiza transações simples entre duas pessoas, atualizando o saldo de suas carteiras, enviando notificações e validando a autorização da transação através de um serviço externo.

## Índice

- [Instalação](#instalação)
- [Arquitetura](#arquitetura)

## Instalação

- Clone o repositório do projeto.

```bash
  git clone git@github.com:KayroDanyell/hyperf-transaction-api.git
```

- Faça o build do container.

```bash
  docker-compose build --no-cache
```

- Verifique a execução do container

```bash
  docker ps
```

- Execute o container

```bash
  docker-composer up -d
```

- Renomeie o *.env.example* para *.env*
```bash
  cp .env.example .env
```
- Preencha as informações de Autenticação do banco de dados e as URI's dos serviços externos no *.env*


## Arquitetura

Esta API utiliza uma arquitetura de camadas para reduzir acomplamento e possibilitar maior reutilização
e manutenabilidade do código, com camadas de **DTO** para tranferência de dados entre camadas com formato específico ,
**Repository** para abstrair acesso e manipulação do Banco de dados, camada de **Service** para encapsular lógica e regra de negócio. 
Também são utilizadas Interfaces para aplicar Inversão de Dependência e Injeção de Dependências e seguir os conceitos de [SOLID](https://en.wikipedia.org/wiki/SOLID).

**Design Patterns Utilizados:** 
- Observer Pattern para envios Notificações para multiplos usuários.
- Decorator Pattern para definição de vários tipos de notificações, requests externas


## Tecnologias

**Back-end:** Php 8.3 - Framework HyperF 3.0

## Testes

Execute a suíte de testes

```bash
  composer test
```