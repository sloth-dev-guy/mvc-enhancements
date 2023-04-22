<?php

namespace SlothDevGuy\MVCEnhancements\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use SlothDevGuy\MVCEnhancements\Interfaces\ArrayOfValueObjects as ArrayOfValueObjectsInterface;
use SlothDevGuy\MVCEnhancements\Interfaces\ValueObject;
use SlothDevGuy\MVCEnhancements\ValueObjects\Exception\InvalidValueObjectException;

/**
 * Class ArrayOfValueObjects
 * @package SlothDevGuy\MVCEnhancements\ValueObjects
 */
abstract class ArrayOfValueObjects implements ArrayOfValueObjectsInterface
{
    /**
     * Stack of value objects
     * @var array
     */
    protected array $items = [];

    /**
     * @param array|Arrayable $items
     * @throws InvalidValueObjectException
     */
    public function __construct(array|Arrayable $items = [])
    {
        $items = $items instanceof Arrayable? $items->toArray() : $items;

        $this->push(...$items);
    }

    /**
     * @inheritDoc
     * @param ...$valueObjects
     * @return $this
     * @throws InvalidValueObjectException
     */
    public function push(...$valueObjects): static
    {
        foreach ($valueObjects as $valueObject){
            $this->add($valueObject);
        }

        return $this;
    }

    /**
     * @inheritDoc
     * @param ValueObject $valueObject
     * @param string|null $key
     * @return $this
     * @throws InvalidValueObjectException
     */
    public function add(mixed $valueObject, string $key = null): static
    {
        $valueObject = $this->asValueObject($valueObject);

        if(is_null($key)){
            $this->items[] = $valueObject;
        }
        else{
            $this->items[$key] = $valueObject;
        }

        return $this;
    }

    /**
     * @inheritDoc
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @inheritDoc
     * @return $this
     */
    public function clear(): static
    {
        $this->items = [];

        return $this;
    }

    /**
     * @inheritDoc
     * @param callable $map
     * @return array
     */
    public function map(callable $map): array
    {
        return array_map($map, $this->items);
    }

    /**
     * @inheritDoc
     * @param callable $iterator
     * @return static
     */
    public function each(callable $iterator): static
    {
        $this->map($iterator);

        return $this;
    }

    /**
     * @inheritDoc
     * @return Collection
     */
    public function collect(): Collection
    {
        return collect($this->items);
    }

    /**
     * @inheritDoc
     * @return array
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function toArray()
    {
        return $this->map(fn(ValueObject $valueObject) => $valueObject->value());
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  string  $offset
     * @return ValueObject|null
     */
    public function offsetGet($offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * Set the item at a given offset.
     *
     * @param string $offset
     * @param ValueObject $value
     * @return void
     * @throws InvalidValueObjectException
     */
    public function offsetSet($offset, $value): void
    {
        $this->add($value, $offset);
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string  $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
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

    /**
     * @inheritDoc
     * @return mixed|Collection
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function object(): mixed
    {
        return $this->collect()->map(fn(ValueObject $valueObject) => $valueObject->object());
    }

    /**
     * @inheritDoc
     * @return mixed|Collection
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function value(): mixed
    {
        return $this->toArray();
    }

    /**
     * @inheritDoc
     * @return mixed|array
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function cast(): mixed
    {
        return $this->collect()->map(fn(ValueObject $valueObject) => $valueObject->cast())->toArray();
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function toString(): string
    {
        return $this->toJson();
    }

    /**
     * @inheritDoc
     * @return mixed
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function jsonSerialize(): mixed
    {
        return $this->map(fn(ValueObject $valueObject) => $valueObject->jsonSerialize());
    }

    /**
     * Transform the value as a value object
     *
     * @param mixed $value
     * @return ValueObject
     * @throws InvalidValueObjectException
     */
    protected function asValueObject(mixed $value) : ValueObject
    {
        $exceptedClass = static::valueObjectClass();
        $valueObject = null;

        if(!($value instanceof ValueObject)){
            $valueObject = AttributesConverter::makeValueObject($exceptedClass, $value);
        }

        if(!($valueObject instanceof $exceptedClass)){
            $actualClass = get_class($value);

            throw new InvalidValueObjectException("only value object of {$exceptedClass} are allowed, {$actualClass} provided");
        }

        return $valueObject;
    }
}
