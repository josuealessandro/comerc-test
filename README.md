# Teste de Emprego - Empresa Comerc

Este é um projeto de aplicação desenvolvido como parte de um teste de emprego para a Empresa Comerc. A aplicação é construída usando o framework Lumen e possui recursos para gerenciamento de pedidos, clientes e produtos.

## Pré-requisitos

Antes de começar, certifique-se de ter os seguintes requisitos instalados em sua máquina:

- Docker
- Docker Compose

## Como Iniciar
Este guia descreve como configurar e executar essa aplicação em um ambiente Docker usando Docker Compose. Certifique-se de que o Docker e o Docker Compose estejam instalados em sua máquina antes de prosseguir.

## Configuração do Ambiente
Clone este repositório em sua máquina local:

```bash
git clone https://github.com/josuealessandro/comerc-test.git
```


Navegue até o diretório do projeto:

```bash
cd comerc-test
```

Crie um arquivo .env a partir do exemplo fornecido:

```bash
cp .env.example .env
```

## Iniciando a Aplicação

Inicie os contêineres:

```bash
docker-compose up -d
```
> **Observação:** Dependendo da versão do docker e do docker compose o comando pode ser `docker compose`

Instale as dependências.

```bash
docker exec comerc_app composer install
```

Execute as migrações do banco de dados para criar as tabelas necessárias:

```bash
docker-compose exec comerc_app php artisan migrate --seed
```

## Executando Testes
Você pode executar os testes para garantir que a aplicação esteja funcionando corretamente:

```bash
docker exec comerc_app vendor/bin/phpunit
```

## Documentação da Aplicação
Essa aplicação tem um swagger, no link:
[http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)
> **Observação:** Cuidado com a porta usada na execução, se precisar trocar se atente para trocar no link e no env

## Parando a Aplicação
Para parar a aplicação e os contêineres Docker em execução, você pode usar o seguinte comando:

```bash
docker-compose down
```
