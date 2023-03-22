<?php

namespace SlothDevGuy\MVCEnhancements\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Validation\ValidationException;
use SlothDevGuy\MVCEnhancements\Interfaces\EntityValueObject as ValuesObjectInterface;

/**
 * Class ValuesObject
 * @package SlothDevGuy\MVCEnhancements\ValueObjects
 * ValuesObject
 * and create a new one called ArrayOfValuesObject
 */
class EntityValueObject implements ValuesObjectInterface
{
    /**
     * @var array
     */
    protected static array $defaultRules = [];

    /**
     * @var array
     */
    protected array $attributes = [];

    /**
     * @var AttributesConverter
     */
    protected AttributesConverter $change;

    /**
     * If true only the validated attributes will be stored
     *
     * @var bool
     */
    protected bool $strict = false;

    /**
     * @param array|Arrayable|null $attributes
     * @param bool $strict
     * @param array $rules
     * @throws ValidationException
     */
    public function __construct(array|Arrayable $attributes = null, bool $strict = false, array $rules = [])
    {
        $this->strict = $this->strict || $strict;

        $this->change = new AttributesConverter(array_merge($rules, static::$defaultRules));

        $this->apply($attributes ?? []);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     * @throws ValidationException
     */
    public function __set(string $name, mixed $value): void
    {
        $this->set($name, $value);
    }

    /**
     * @param array|Arrayable $values
     * @return static
     * @throws ValidationException
     */
    protected function apply(array|Arrayable $values) : static
    {
        if($values instanceof Arrayable){
            $values = $values->toArray();
        }

        $valueObjects = $this->change->toValueObjects($values);

        $validAttributes = $this->change->validate($values);

        $ruleKeys = array_keys($this->change->rules());

        $attributes = $this->strict? [] : collect($values)->except($ruleKeys)->toArray();

        $this->attributes = array_merge($attributes, $validAttributes, $valueObjects);

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null) : mixed
    {
        return data_get($this->attributes, $key, $default);
    }

    /**
     * @inheritDoc
     * @param string $key
     * @param mixed $value
     * @return static
     * @throws ValidationException
     */
    public function set(string $key, mixed $value): static
    {
        $attributes = $this->attributes;

        data_set($attributes, $key, $value);

        $this->apply($attributes);

        return $this;
    }

    /**
     * @return array
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function toArray()
    {
        return $this->map();
    }

    /**
     * @param int $options
     * @return false|string
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->toJson();
    }

    /**
     * @param array|Arrayable $newAttributes
     * @return $this
     */
    public function cloneWith(array|Arrayable $newAttributes = []): static
    {
        if($newAttributes instanceof Arrayable){
            $newAttributes = $newAttributes->toArray();
        }

        return new static(array_merge($this->toArray(), $newAttributes));
    }

    /**
     * @return mixed
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function object(): mixed
    {
        return (object) $this->attributes;
    }

    /**
     * @return mixed
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function value(): mixed
    {
        return $this->map();
    }

    /**
     * @return mixed
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function cast(): mixed
    {
        return $this->change->castValues($this->attributes);
    }

    public function map(): array
    {
        return $this->change->mapValues($this->attributes);
    }

    /**
     * @return mixed
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function jsonSerialize(): mixed
    {
        return $this->toJson();
    }
}
