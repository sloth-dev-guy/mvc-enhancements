<?php

namespace Tests\ValueObjects\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Jenssegers\Mongodb\Eloquent\Model;
use SlothDevGuy\MVCEnhancements\ValueObjects\BSONCarbonValueObject;
use SlothDevGuy\MVCEnhancements\ValueObjects\Casts\AsBSONCarbonValue;

/**
 * Class DummyModel
 * @package Tests\Unit\Eloquent
 *
 * @property string foo_value
 * @property BSONCarbonValueObject some_date
 * @property EntityValueObject options
 */
class TestModel extends Model
{
    use HasTimestamps;

    protected $connection = 'mongodb';

    protected $collection = 'test';

    protected $guarded = ['id', '_id'];

    /**
     * @var array
     */
    protected $casts = [
        'some_date' => AsBSONCarbonValue::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'options' => ArrayValueObjectCast::class,
    ];
}
