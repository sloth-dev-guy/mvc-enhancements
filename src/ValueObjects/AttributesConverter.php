<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

namespace SlothDevGuy\MVCEnhancements\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Validation\ValidationException;
use SlothDevGuy\MVCEnhancements\Interfaces\ValueObject;
use SlothDevGuy\MVCEnhancements\Interfaces\EntityValueObject as ValuesObjectInterface;
use Stringable;

/**
 * Class AttributesConverter
 * @package SlothDevGuy\MVCEnhancements\ValueObjects
 */
class AttributesConverter
{
    /**
     * @var array
     */
    protected array $validationRules = [];

    public function __construct(
        protected array $rules,
    )
    {
        $this->validationRules = array_filter(array_map(fn($rules) => data_get($rules, 'validations'), $this->rules));
    }

    /**
     * @param array $attributes
     * @return array
     * @throws ValidationException
     */
    public function validate(array $attributes) : array
    {
        return validator($attributes, $this->validationRules)
            ->validate();
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        return $this->rules;
    }

    public function toValueObjects(array $attributes) : array
    {
        $newAttributes = [];

        foreach ($this->rules as $key => $rule){
            $value = data_get($attributes, $key);
            $type = null;
            $arguments = [];

            if($value === null) continue;

            if(is_string($rule)){
                $type = $rule;
            }
            elseif(is_array($rule)){
                $type = data_get($rule, 'value_object');

                $arguments = [
                    'strict' => data_get($rule, 'strict', false),
                    'rules' => data_get($rule, 'rules', []),
                ];
            }

            $value = $type === null?
                $value :
                $this->makeValueObject($type, $value, $arguments);

            data_set($newAttributes, $key, $value);
        }

        return $newAttributes;
    }

    /**
     * @param array $attributes
     * @return array
     */
    public function mapValues(array $attributes) : array
    {
        return array_map(fn($value) => $this->mapValue($value), $attributes);
    }

    /**
     * @param ValueObject|Arrayable|Stringable|mixed $value
     * @return mixed
     */
    public function mapValue(mixed $value) : mixed
    {
        if($value instanceof ValueObject){
            $value = $value->value();
        }
        elseif($value instanceof Arrayable){
            $value = $value->toArray();
        }
        elseif ($value instanceof Stringable){
            $value = $value->toString();
        }
        elseif (is_object($value)){
            if(method_exists($value, 'toArray')){
                $value = $value->toArray();
            }
            elseif (method_exists($value, 'toString')){
                $value = $value->toString();
            }
        }

        return $value;
    }

    /**
     * @param array $attributes
     * @return array
     */
    public function castValues(array $attributes) : array
    {
        return array_map(fn($value) => $this->castValue($value), $attributes);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function castValue(mixed $value) : mixed
    {
        if($value instanceof ValueObject){
            return $value->cast();
        }

        return $this->mapValue($value);
    }

    /**
     * @param string $type
     * @param mixed $value
     * @param array $arguments
     * @return ValueObject
     */
    public static function makeValueObject(string $type, mixed $value, array $arguments = []) : ValueObject
    {
        //@todo define the validation method
        return is_a($type, ValuesObjectInterface::class, true)?
            new $type($value, ...array_values($arguments)) :
            new $type($value);
    }
}
