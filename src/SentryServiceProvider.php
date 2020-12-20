<?php

namespace Febalist\Laravel\Sentry;

use Febalist\Laravel\Sentry\Http\Middleware\SentryContext;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
                'host' => Str::after(config('app.url'), '://'),
                'console' => app()->runningInConsole(),
                'command' => implode(' ', request()->server('argv', [])) ?: null,
                'server_name' => gethostname(),
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sentry.php', 'sentry');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/SentryServiceProvider.stub' => app_path('Providers/SentryServiceProvider.stub'),
            ], 'sentry-provider');
        }
    }
}
