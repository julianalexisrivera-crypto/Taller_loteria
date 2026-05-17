# ── Imagen base oficial PHP + Apache ──────────────────────────
FROM php:8.2-apache

# Extensión mysqli para la conexión a base de datos
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copiar todo el código fuente al directorio raíz de Apache
COPY . /var/www/html/

# Permisos correctos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exponer el puerto HTTP
EXPOSE 80
