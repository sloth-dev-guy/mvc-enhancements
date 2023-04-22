<?php

namespace SlothDevGuy\MVCEnhancements\ValueObjects\Traits;

use SlothDevGuy\MVCEnhancements\Interfaces\ValueObject;

/**
 * Trait AttributesToArray
 * @package SlothDevGuy\MVCEnhancements\ValueObjects\Traits
 */
trait AttributesToArray
{
    /**
     * @inheritdoc
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        // Because the original Eloquent never returns objects, we convert
        // Value objects to an array representation.
        foreach ($attributes as $key => &$value) {
            if ($value instanceof ValueObject) {
                $value = $value->value();
            }
        }

        return $attributes;
    }
}
