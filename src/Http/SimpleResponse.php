<?php

namespace SlothDevGuy\MVCEnhancements\Http;

use Illuminate\Contracts\Support\Arrayable;
use SlothDevGuy\MVCEnhancements\Interfaces\ResponseSchema;

/**
 * Class BaseResponse
 * @package SlothDevGuy\LaravelNice
 */
class SimpleResponse implements ResponseSchema
{
    /**
     * @var Arrayable
     */
    protected Arrayable $payload;

    /**
     * @param Arrayable|null $payload
     */
    public function __construct(Arrayable $payload = null)
    {
        $payload && $this->setPayload($payload);
    }

    /**
     * @param Arrayable $payload
     * @return $this
     */
    public function setPayload(Arrayable $payload): static
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * We map all the data as it is
     *
     * @return array
     */
    public function map(): array
    {
        return $this->payload->toArray();
    }

    /**
     * @inheritdoc
     * @param string $key
     * @param mixed $value
     * @return static
     */
    public function set(string $key, mixed $value): static
    {
        data_set($this->payload, $key, $value);

        return $this;
    }


    /**
     * @inheritDoc
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return data_get($this->payload, $key, $default);
    }

    /**
     * @inheritDoc
     * @return array
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function toArray()
    {
        return $this->map();
    }

    /**
     * @inheritDoc
     * @param $options
     * @return false|string
     * @noinspection PhpMissingReturnTypeInspection
     * @noinspection PhpMissingParamTypeInspection
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
