## Install Project

If you don't have an Apple Silicon

```bash
    touch docker-compose.override

    # Past in
    version: '3'
    services:
        selenium:
            image: 'selenium/standalone-chrome'
```

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer config http-basic.nova.laravel.com {NOVA MAIL} {NOVA PASSWORD} \
    && composer install --ignore-platform-reqs

sail up -d
sail artisan key:generate
sail artisan migrate --seed
```

For front-end

```bash
    make watch

    # For SSR
    make ssr
```

## Tests

We use Behat in front of Dusk

```bash
make behat
```
