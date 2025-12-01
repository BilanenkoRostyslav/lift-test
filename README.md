Installation guide:

1. Clone project: use command
   git clone ``https://github.com/BilanenkoRostyslav/lift-test.git``
2. Create ``.env`` file. Use command
   ``cp .env.example .env``
3. Run command
   ``docker-compose up -d``
4. Run command
   ``docker exec -u www-data -w /var/www/html php-fpm-lift composer install``
5. Run command
   ``docker exec -it php-fpm-lift php bin/console messenger:consume async -vv``
6. Go to
   ``localhost/api/doc``