# Usando a imagem oficial do PHP com Laravel pré-configurado
FROM php:8.1-fpm

# Definindo diretório de trabalho
WORKDIR /var/www/html

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql zip

# Instalar a extensão Redis
RUN pecl install redis && docker-php-ext-enable redis

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar todo o código Laravel para o container
COPY . /var/www/html

# Ajustar permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Instalar dependências do Laravel
RUN composer install

# Expor a porta 9000 para comunicação com o Nginx
EXPOSE 9000

CMD ["php-fpm"]
