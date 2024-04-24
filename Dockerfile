FROM richarvey/nginx-php-fpm:latest

RUN rm -rf /var/www/html
COPY . /var/www/html

WORKDIR "/var/www/html"
CMD ["/start.sh"]
