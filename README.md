# 🎰 Lotería — Juego de Imágenes v1.1

Juego de lotería/tragaperras desarrollado en PHP con arquitectura MVC.

## Requisitos
- PHP 8.2+ con extensión `mysqli`
- MariaDB / MySQL
- Apache (o Docker)

## Instalación rápida con Docker

```bash
docker-compose up -d
```
Luego abre: http://localhost:8080

## Sin Docker (XAMPP / WAMP)
1. Copia la carpeta en `htdocs/` o `www/`
2. Importa `juego.sql` en phpMyAdmin
3. Accede a `http://localhost/loteria-v1.1/`

## Comandos de desarrollo

```bash
# PHP Lint
bash lint.sh

# Pruebas unitarias
php test_jugar.php
```

## Cambios v1.1
- Puntaje inicial cambiado de 1000 → **2000**
- Interfaz rediseñada (tema casino nocturno)
- PHP Lint integrado como paso CI
- Pruebas unitarias del método `jugar()`
- Dockerizado con MariaDB + phpMyAdmin
