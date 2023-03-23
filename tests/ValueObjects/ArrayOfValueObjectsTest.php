<?php

namespace Tests\ValueObjects;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use MongoDB\BSON\UTCDateTime;
use SlothDevGuy\MVCEnhancements\Interfaces\ValueObject;
use SlothDevGuy\MVCEnhancements\ValueObjects\ArrayOfValueObjects;
use SlothDevGuy\MVCEnhancements\ValueObjects\BSONCarbonValueObject;
use SlothDevGuy\MVCEnhancements\ValueObjects\Exception\InvalidValueObjectException;
use Tests\TestCase;
use Tests\ValueObjects\Eloquent\Model\EntityValueObject;

/**
 * Class ArrayOfValueObjectsTest
 * @package Tests\ValueObjects
 */
class ArrayOfValueObjectsTest extends TestCase
{
    /**
     * @return void
     * @throws InvalidValueObjectException
     * @noinspection PhpUndefinedMethodInspection
     */
    public function testArrayOfValueObjects(): void
    {
        $dateTimes = [$carbon = now(), $string = date('Y-m-d H:i:s')];

        $valueObjects = $this->newArrayValuesObject($dateTimes);

        $valueObjects->add($dateTime = fake()->dateTime, 2);

        $this->assertEquals(3, $valueObjects->count());

        $valueObjects->each(fn(ValueObject $valueObject) => $this->assertInstanceOf(BSONCarbonValueObject::class, $valueObject));

        $this->assertEquals($carbon->toISOString(), $valueObjects[0]->value());
        $this->assertEquals($string, $valueObjects[1]->object()->format('Y-m-d H:i:s'));
        $this->assertEquals($dateTime->getTimestamp(), $valueObjects[2]->object()->getTimestamp());

        $serializedValueObject = new $valueObjects(collect(json_decode(json_encode($valueObjects))));
        $serializedValueObject->each(fn(ValueObject $valueObject) => $this->assertInstanceOf(BSONCarbonValueObject::class, $valueObject));

        $valueObjects->clear();

        $this->assertEmpty($valueObjects->toArray());

        $valueObjects[0] = $dateTime;

        $this->assertTrue(isset($valueObjects[0]));

        $this->assertInstanceOf(Collection::class, $collection = $valueObjects->object());
        $this->assertEquals($dateTime->getTimestamp(), $collection->first()->getTimestamp());

        $valueObjects->value()->each(fn(string $date) => $this->assertEquals($date, Carbon::parse($date)->toISOString()));
        $cast = $valueObjects->cast();

        array_map(fn(UTCDateTime $date) => $this->assertEquals($date->toDateTime()->getTimestamp(), Carbon::parse($date->toDateTime())->getTimestamp()), $cast);

        $this->assertNotEmpty((string) $valueObjects);

        unset($valueObjects[0]);

        $this->assertEmpty($valueObjects->toArray());
    }

    /**
     * @return void
     */
    public function testInvalidValueObject(): void
    {
        $this->expectException(InvalidValueObjectException::class);

        $this->newArrayValuesObject([
            $this->invalidValidObject()
        ]);
    }

    /**
     * @param array $values
     * @return ArrayOfValueObjects
     */
    protected function newArrayValuesObject(array $values): ArrayOfValueObjects
    {
        return new class($values) extends ArrayOfValueObjects
        {
            /**
             * @return string
             */
            public static function valueObjectClass(): string
            {
                return BSONCarbonValueObject::class;
            }
        };
    }

    /**
     * @return ValueObject
     */
    public function invalidValidObject(): ValueObject
    {
        return new class extends EntityValueObject
        {

        };
    }
}
