<?php

namespace SlothDevGuy\MVCEnhancements\Interfaces;

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
     * @param ResponseSchema|null $responseSchema
     * @return ResponseSchema
     */
    public function response(ResponseSchema $responseSchema = null) : ResponseSchema;
}
