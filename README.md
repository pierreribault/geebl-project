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

## Payment

1. Check your .env
```
STRIPE_KEY=pk_test_51L1IXDIwaPxaMAE4VKDFY6JiR05BopCAE5T4MKXj1gJ6Lksmy6N3YsI8m77PN6OEE76yp2MhG92cKjn4NbZQN0GW00oB0BNyL3
STRIPE_SECRET=sk_test_51L1IXDIwaPxaMAE4LYYiYHCP0nmnXWjNaDOYBRQtHya3Him5VLXk68bGxum0QQE4Os9HXh9fOwmjDxhibNe4qawR00Esy0Xv70
```

2. Install [Stripe CLI](https://stripe.com/docs/stripe-cli)

3. Run the "listen" command
```
stripe listen --forward-to localhost/webhooks/stripe
```

You can find webhooks under the folder `App\Http\Controllers\Webhooks`.

## Tests

We use Behat in front of Dusk

```bash
make behat
```
