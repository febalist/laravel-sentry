<?php

namespace Febalist\Laravel\Sentry;

use Sentry\State\Hub;
use Sentry\State\Scope;
use Throwable;

class Sentry
{
    protected static $user = [];
    protected static $tags = [];

    public static function capture(Throwable $e)
    {
        if (static::enabled()) {
            static::instance()->captureException($e);
        }
    }

    public static function user(array $context)
    {
        if (static::enabled()) {
            static::$user = array_merge(static::$user, $context);

            static::instance()->configureScope(function (Scope $scope) {
                $scope->setUser(static::$user);
            });

            static::javascript();
        }
    }

    public static function tags(array $context)
    {
        if (static::enabled()) {
            static::$tags = array_merge(static::$tags, $context);

            static::instance()->configureScope(function (Scope $scope) {
                foreach (static::$tags as $key => $value) {
                    if ($value === null) {
                        continue;
                    }

                    if (is_bool($value)) {
                        $value = $value ? 'true' : 'false';
                    } else {
                        $value = (string) $value;
                    }

                    $scope->setTag($key, $value);
                }
            });

            static::javascript();
        }
    }

    public static function enabled()
    {
        return app()->bound('sentry') && config('sentry.dsn');
    }

    /** @return Hub */
    public static function instance()
    {
        return app('sentry');
    }

    protected static function javascript()
    {
        javascript('sentry', [
            'dsn' => config('sentry.dsn'),
            'release' => config('sentry.release'),
            'traces_sample_rate' => config('sentry.traces_sample_rate'),
            'user' => static::$user,
            'tags' => static::$tags,
        ]);
    }
}
