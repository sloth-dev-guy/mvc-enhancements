<?php

use Carbon\Carbon;
use Faker\Factory;
use Faker\Generator;
use Psr\Log\LoggerInterface;

if (! function_exists('fake') && class_exists(Factory::class)) {
    /**
     * Get a faker instance. (in case the project doesn't include the laravel/framework package
     *
     * @param null $locale
     * @return Generator
     */
    function fake($locale = null): Generator
    {
        if (app()->bound('config')) {
            $locale ??= app('config')->get('app.faker_locale');
        }

        $locale ??= 'en_US';

        $abstract = Generator::class.':'.$locale;

        if (! app()->bound($abstract)) {
            app()->singleton($abstract, fn () => Factory::create($locale));
        }

        return app()->make($abstract);
    }
}

if (! function_exists('now')) {
    /**
     * Create a new Carbon instance for the current time.
     *
     * @param DateTimeZone|string|null $tz
     * @return Carbon
     */
    function now(DateTimeZone|string $tz = null): Carbon
    {
        return Carbon::now($tz);
    }
}

if (! function_exists('logger')) {
    /**
     * Log a debug message to the logs.
     *
     * @param  string|null  $message
     * @param  array  $context
     * @return LoggerInterface
     */
    function logger(string $message = null, array $context = []): LoggerInterface
    {
        if (!is_null($message)) {
            logger()->debug($message, $context);
        }

        return app('Psr\Log\LoggerInterface');
    }
}

