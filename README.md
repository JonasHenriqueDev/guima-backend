
## Stack utilizada


**Back-end:** PHP, Laravel 10, Postgres, Redis


## Rodando localmente

Antes de tudo é necessário configurar o arquivo .env

Dentro do projeto existe um arquivo .env.example

Para rodar a aplicação execute:

```bash
  docker compose up -d
```

```bash
  docker compose exec app bash
```
```bash
  composer install
```
```bash
  php artisan key:generate
```
```bash
  php artisan migrate:fresh --seed
```
