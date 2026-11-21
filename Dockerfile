FROM php:8.6-apache
RUN docker-php-ext-install mysqli \
 && a2enmod proxy proxy_http
COPY docker/geoserver-proxy.conf /etc/apache2/conf-available/geoserver-proxy.conf
COPY docker/security.conf        /etc/apache2/conf-available/security-luphuket.conf
RUN a2enconf geoserver-proxy security-luphuket
