<?php

namespace Tests\ValueObjects;

use Illuminate\Validation\ValidationException;
use SlothDevGuy\MVCEnhancements\ValueObjects\EntityValueObject;
use stdClass;
use Tests\TestCase;

/**
 * Class EntityValueObjectTest
 * @package Tests\ValueObjects
 */
class EntityValueObjectTest extends TestCase
{
    /**
     * @return void
     * @throws ValidationException
     */
    public function testEntityValueObjectCases(): void
    {
        $valueObject = $this->newEntityValueObject(collect([
            'message' => $message = fake()->paragraph,
        ]));

        $this->assertStringContainsString($message, (string) $valueObject);

        $this->assertInstanceOf(stdClass::class, $valueObject->object());

        $this->assertEquals($message, $valueObject->object()->message);

        $newValueObject = $valueObject->cloneWith(collect([
            'title' => $title = fake()->title,
        ]));

        $values = $newValueObject->value();
        $this->assertNotEmpty($values);

        $this->assertEquals($message, $values['message']);
        $this->assertEquals($title, $values['title']);
        $this->assertEquals(json_encode($values), json_encode($newValueObject));

        $this->assertEquals($message, $newValueObject->get('message'));
        $this->assertEquals($title, $newValueObject->get('title'));
    }

    /**
     * @param $values
     * @return EntityValueObject
     */
    protected function newEntityValueObject($values): EntityValueObject
    {
        return new class($values) extends EntityValueObject
        {

        };
    }
}
