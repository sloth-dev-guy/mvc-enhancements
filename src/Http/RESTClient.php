<?php

namespace SlothDevGuy\MVCEnhancements\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * Class RESTClient
 * @package SlothDevGuy\MVCEnhancements\Http
 */
abstract class RESTClient
{
    const ROUTE_PARAMETER_PATTERN = '/(\{([a-z0-9_-]+)\})+/i';

    /**
     * @var string
     */
    protected string $serverUrl;

    /**
     * @var array|string[]
     */
    protected static array $routes = [];

    /**
     * @var PendingRequest
     */
    protected PendingRequest $httpClient;

    /**
     * @param string|null $serverUrl
     */
    public function __construct(string $serverUrl = null)
    {
        $this->serverUrl = $serverUrl? : static::serverURl();

        $this->httpClient = Http::baseUrl($this->serverUrl);
    }

    /**
     * @return PendingRequest
     */
    public function request() : PendingRequest
    {
        return $this->httpClient;
    }

    /**
     * @return array
     */
    public static function routes() : array
    {
        return static::$routes;
    }

    /**
     * @param string $name
     * @param array $parameters
     * @return string
     */
    public static function route(string $name, array $parameters = []) : string
    {
        $route = data_get(static::routes(), $name);

        $route = preg_replace_callback(static::ROUTE_PARAMETER_PATTERN, function ($matches) use(&$parameters){
            $key = array_pop($matches);
            $default = array_shift($matches);

            if(isset($parameters[$key])){
                $value = $parameters[$key];

                unset($parameters[$key]);

                return $value;
            }

            return $default;
        }, $route);

        if(count($parameters) > 0){
            $query = http_build_query($parameters);
            $route .= "?$query";
        }

        return $route;
    }

    /**
     * Return the server url
     *
     * @return string
     */
    abstract public static function serverURl() : string;
}
