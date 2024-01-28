<?php

namespace SlothDevGuy\MVCEnhancements\Interfaces;

use Symfony\Component\HttpFoundation\Response;

/**
 * Interface Action
 * @package SlothDevGuy\MVCEnhancements\Interfaces
 * @pattern CommandPattern
 * @link https://refactoring.guru/design-patterns/command
 */
interface Command
{
    /**
     * @return Request
     */
    public function request() : Request;

    /**
     * Executes all this use case steps
     *
     * @return void
     */
    public function execute(): void;

    /**
     * Gets or sets the response schema returned by this action
     *
     * @param ResponseSchema|Response|null $responseSchema
     * @return ResponseSchema|Response
     */
    public function response(ResponseSchema|Response $responseSchema = null) : ResponseSchema|Response;
}
