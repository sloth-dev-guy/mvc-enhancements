<?php

namespace Tests\ValueObjects\Eloquent\Model;

use SlothDevGuy\MVCEnhancements\ValueObjects\BSONCarbonValueObject;
use SlothDevGuy\MVCEnhancements\ValueObjects\EntityValueObject as BaseArrayValueObject;

/**
 * Class ArrayValueObject
 * @package Tests\Unit\Eloquent
 *
 * @property BSONCarbonValueObject date
 * @property string message
 */
class EntityValueObject extends BaseArrayValueObject
{
    /**
     * @var array
     */
    protected static array $defaultRules = [
        'date' => BSONCarbonValueObject::class,
        'message' => [
            'validations' => ['sometimes', 'required', 'string', 'max:255'],
        ],
    ];
}
