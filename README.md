# Laravel Sentry

```bash
composer require febalist/laravel-sentry
yarn add --dev link:vendor/febalist/laravel-sentry
```

`App/Exceptions/Handler.php`

```php
use Febalist\Laravel\Sentry\Sentry;

//...

public function register()
{
    $this->reportable(function (Throwable $e) {
        Sentry::capture($e);
    });
}
```

`resources/js/bootstrap.js`

```javascript
require('@febalist/laravel-sentry');
```
