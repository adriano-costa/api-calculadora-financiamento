# Uma imagem com a versão do php necessária para o projeto e com os drivers para conexão com o banco de dados
FROM namoshek/php-mssql:8.2-cli

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . /app

RUN composer install

# usar essa linha apenas se estiver fazendo deploy para um banco em branco
# RUN php artisan migrate:fresh --seed

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000
