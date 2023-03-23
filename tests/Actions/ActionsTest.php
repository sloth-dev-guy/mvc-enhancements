<?php

namespace Tests\Actions;

use SlothDevGuy\MVCEnhancements\DesignPatterns\Action;
use SlothDevGuy\MVCEnhancements\Http\SimpleRequest;
use SlothDevGuy\MVCEnhancements\Http\SimpleResponse;
use Tests\TestCase;

/**
 * Class ActionsTest
 * @package Tests\Actions
 */
class ActionsTest extends TestCase
{
    /**
     * @return void
     */
    public function testActionMethods(): void
    {
        $action = $this->newAction($request = new SimpleRequest([
            'message' => $message = fake()->paragraph,
        ]));

        $this->assertEquals($message, $action->request()->get('message'));

        $this->assertInstanceOf(SimpleResponse::class, $action->response());

        $newResponse = new SimpleResponse();

        $this->assertEquals($newResponse, $action->response($newResponse));

        $action->execute();

        $this->assertTrue($action->executed);

        $this->assertEquals($request->toJson(), $action->response()->toJson());
    }

    /**
     * @param SimpleRequest $request
     * @return Action
     */
    protected function newAction(SimpleRequest $request): Action
    {
        return new class($request) extends Action
        {
            /**
             * A simple flag for assertions
             * @var bool
             */
            public bool $executed = false;

            /**
             * @return void
             */
            public function execute(): void
            {
                $this->response()
                    ->setPayload($this->request());

                $this->executed = true;
            }
        };
    }
}
