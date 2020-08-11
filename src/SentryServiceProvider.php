<?php

namespace Febalist\Laravel\Sentry;

use Febalist\Laravel\Sentry\Http\Middleware\SentryContext;
use Illuminate\Support\ServiceProvider;

class SentryServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (Sentry::enabled()) {
            app('router')
                ->pushMiddlewareToGroup('web', SentryContext::class)
                ->pushMiddlewareToGroup('api', SentryContext::class);

            Sentry::tags([
                'name' => config('app.name'),
                'host' => str_after(config('app.url'), '://'),
                'console' => app()->runningInConsole(),
                'command' => implode(' ', request()->server('argv', [])) ?: null,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sentry.php', 'sentry');
        $this->mergeConfigFrom(__DIR__.'/../config/febalist-sentry.php', 'febalist-sentry');
    }
}
