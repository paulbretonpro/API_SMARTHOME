
# API Smarthome

The aim of this project is to centralise, store and supply formatted data.

This API (Application interface) has been developed using the Laravel framework.

## Installation

**Prerequis** : 
- [Docker](https://docs.docker.com/engine/install/)
- [Lire documentation laravel](https://laravel.com/docs/10.x/sail)

**Importer dependances**

```php
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

**Configuration .env**
```bash
cp .env.exemple .env
```
Add your parameters

**Container**

Launching code and database containers
```bash
  ./vendor/bin/sail up
```

## Authors

Do not hesitate to contact me at the address :
- paul.breton.dev@gmail.com
- [@paulbretonpro](https://www.github.com/paulbretonpro)
    
