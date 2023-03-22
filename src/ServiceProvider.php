<?php

namespace SlothDevGuy\MVCEnhancements;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use SlothDevGuy\MVCEnhancements\Http\SimpleRequest;

/**
 * Class ServiceProvider
 * @package SlothDevGuy\MVCEnhancements
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        $this->app->resolving(
            SimpleRequest::class,
            fn($request, $app) => $request::createFrom($app['request'], $request)
        );

        $this->app->afterResolving(
            SimpleRequest::class,
            fn(SimpleRequest $request) => $request->validate()
        );
    }
}
