
## Stack utilizada


**Back-end:** PHP, Laravel 10, Postgres, Redis, NGINX


## Rodando localmente

Antes de tudo é necessário configurar o arquivo .env

Dentro do projeto existe um arquivo .env.example

Basta renomear o .env.example para apenas .env

Para rodar a aplicação execute:

```bash
  docker compose up -d
```

```bash
  docker compose exec app bash
```
```bash
  php artisan key:generate
```
```bash
  php artisan migrate:fresh --seed
```

A aplicação estará rodando no servidor NGINX em: http://localhost:8989/

## Documentação

http://localhost:8989/api/docs
