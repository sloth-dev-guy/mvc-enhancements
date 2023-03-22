<?php

namespace Tests\ValueObjects;

use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use MongoDB\BSON\UTCDateTime;
use SlothDevGuy\MVCEnhancements\Interfaces\ValueObject;
use SlothDevGuy\MVCEnhancements\ValueObjects\ArrayOfValueObjects;
use SlothDevGuy\MVCEnhancements\ValueObjects\BSONCarbonValueObject;
use SlothDevGuy\MVCEnhancements\ValueObjects\CarbonValueObject;
use SlothDevGuy\MVCEnhancements\ValueObjects\EntityValueObject;
use Tests\TestCase;

/**
 * Class ValueObjectsTest
 * @package Tests\Unit\ValueObjects
 */
class ValueObjectsTest extends TestCase
{
    /**
     * @return void
     */
    public function testCarbonValueObject() : void
    {
        $valueObject = new CarbonValueObject($date = fake()->dateTime);

        $carbon = $valueObject->object();

        $this->assertTrue($carbon->equalTo($date));

        $isoDate = (new Carbon($date))->toISOString();

        $this->assertEquals($isoDate, $valueObject->value());

        $this->assertEquals($isoDate, $valueObject->toString());

        $this->assertEquals($isoDate, (string) $valueObject);

        $this->assertEquals($isoDate, json_decode(json_encode($valueObject)));

        $this->assertFalse($carbon->equalTo($date->modify('+1 day')));
    }

    public function testBSONCarbonValueObject() : void
    {
        $valueObject = new BSONCarbonValueObject($date = fake()->dateTime);

        $bson = $valueObject->cast();

        $this->assertInstanceOf(UTCDateTime::class, $bson);

        $this->assertEquals($date->getTimestamp(), $bson->toDateTime()->getTimestamp());
    }

    /**
     * @return void
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public function testValuesObject() : void
    {
        $valuesObject = $this->newValuesObject($expected = [
            'title' => fake()->name,
            'observations' => fake()->paragraph,
            'date' => fake()->dateTime,
            'bson' => fake()->dateTime,
            'nest' => [
                'date' => fake()->dateTime,
                'bson' => fake()->dateTime,
            ],
        ]);

        $this->assertEquals($expected['title'], $valuesObject->get('title'));
        $this->assertEquals($expected['observations'], $valuesObject->get('observations'));

        $this->assertInstanceOf(CarbonValueObject::class, $date = $valuesObject->get('date'));
        $this->assertEquals((new Carbon($expected['date']))->toISOString(), (string) $date);

        $this->assertInstanceOf(BSONCarbonValueObject::class, $bson = $valuesObject->get('bson'));
        $this->assertEquals((new Carbon($expected['bson']))->toISOString(), (string) $bson);

        $this->assertInstanceOf(CarbonValueObject::class, $date = $valuesObject->get('nest')->get('date'));
        $this->assertEquals((new Carbon($expected['nest']['date']))->toISOString(), (string) $date);

        $this->assertInstanceOf(BSONCarbonValueObject::class, $bson = $valuesObject->get('nest')->get('bson'));
        $this->assertEquals((new Carbon($expected['nest']['bson']))->toISOString(), (string) $bson);

        echo $valuesObject->toJson(JSON_PRETTY_PRINT);

        var_dump($valuesObject->cast());

        echo json_encode($valuesObject, JSON_PRETTY_PRINT);
    }

    public function testValueObjectValidations() : void
    {
        $this->expectException(ValidationException::class);

        $failedMessages = [null, '', false, fake()->paragraph];

        $object = $ex = null;

        foreach ($failedMessages as $message){
            try{
                $object = $this->newValuesObject(compact('message'));
            }
            catch (ValidationException $ex){ }

            $this->assertNull($object);
        }

        $ex && throw $ex;
    }

    public function testArrayOfValueObjects()
    {
        $dateTimes = [$carbon = now(), $string = date('Y-m-d H:i:s'), $dateTime = fake()->dateTime];

        $valueObjects = $this->newArrayValuesObject($dateTimes);

        $valueObjects->each(fn(ValueObject $valueObject) => $this->assertInstanceOf(BSONCarbonValueObject::class, $valueObject));

        $this->assertEquals($carbon->toISOString(), $valueObjects[0]->value());
        $this->assertEquals($string, $valueObjects[1]->object()->format('Y-m-d H:i:s'));
        $this->assertEquals($dateTime->getTimestamp(), $valueObjects[2]->object()->getTimestamp());
    }

    /**
     * @param array $attributes
     * @return ValueObject
     */
    protected function newValuesObject(array $attributes) : ValueObject
    {
        return new class($attributes) extends EntityValueObject
        {
            protected static array $defaultRules = [
                'date' => CarbonValueObject::class,
                'bson' => BSONCarbonValueObject::class,
                'nest' => [
                    'value_object' => EntityValueObject::class,
                    'rules' => [
                        'date' => CarbonValueObject::class,
                        'bson' => BSONCarbonValueObject::class,
                    ],
                ],
                'message' => [
                    'validations' => ['sometimes', 'required', 'string', 'max:25'],
                ],
            ];
        };
    }

    /**
     * @param array $values
     * @return ArrayOfValueObjects
     */
    protected function newArrayValuesObject(array $values) : ArrayOfValueObjects
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
}
