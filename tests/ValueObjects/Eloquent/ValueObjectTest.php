<?php

namespace Tests\ValueObjects\Eloquent;

use MongoDB\BSON\UTCDateTime;
use SlothDevGuy\MVCEnhancements\Services\MongoDB;
use Tests\TestCase;
use Tests\ValueObjects\Eloquent\Model\EntityValueObject;
use Tests\ValueObjects\Eloquent\Model\TestModel;

/**
 * Class CastMongoDBTypesWithCastsTest
 * @package Tests\Unit\Eloquent
 */
class ValueObjectTest extends TestCase
{
    public function testValueObjects()
    {
        $model = $this->getTestModelInstance();
        $service = new MongoDB($model);
        $service->collection()->drop();

        $options = [
            'date' => fake()->dateTime,
            'message' => fake()->title,
        ];

        $model->foo_value = 'foo-bar';
        $model->some_date = $time = 1671139312.5169;
        $model->options->date = $options['date'];
        $model->options->message = $options['message'];

        $this->assertTrue($model->save());

        $model = $model->refresh();
        $model->touch();
        $model->save();

        $this->assertInstanceOf(EntityValueObject::class, $model->options);
        //it should get the real object or the value object instance instead?
        $this->assertEquals($options['date']->format('Y-m-d H:i:s'), $model->options->date->object()->format('Y-m-d H:i:s'));
        $this->assertEquals($options['message'], $model->options->message);

        $service = new MongoDB($model);
        $results = $service->collection()->find(options: [
            'sort' => [
                '_id' => -1
            ],
            'limit' => 1
        ]);

        $record = collect($results)->first();
        $this->assertEquals($model->foo_value, data_get($record, 'foo_value'));
        /** @var UTCDateTime $someDate */
        $someDate = data_get($record, 'some_date');
        $this->assertEquals((int) $time, $model->some_date->object()->getTimestamp());
        $this->assertEquals($model->some_date->object()->format('Y-m-d H:i:s'), $someDate->toDateTime()->format('Y-m-d H:i:s'));
    }

    protected function getTestModelInstance() : TestModel
    {
        return new TestModel();
    }
}
