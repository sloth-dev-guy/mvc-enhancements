<?php

namespace SlothDevGuy\MVCEnhancements\DesignPatterns;

use SlothDevGuy\MVCEnhancements\DesignPatterns\Traits\DefaultResponseSchema;
use SlothDevGuy\MVCEnhancements\Interfaces\Command;
use SlothDevGuy\MVCEnhancements\Interfaces\Request;
use SlothDevGuy\MVCEnhancements\Interfaces\ResponseSchema;

/**
 * Class BaseAction
 * @package SlothDevGuy\MVCEnhancements
 */
abstract class Action implements Command
{
    use DefaultResponseSchema;

    /**
     * @var ResponseSchema
     */
    protected ResponseSchema $responseSchema;

    /**
     * @param Request $request
     */
    public function __construct(
        private readonly Request $request,
    )
    {
        $this->response($this->defaultResponseSchema());
    }

    /**
     * @inheritDoc
     * @return Request
     */
    public function request() : Request
    {
        return $this->request;
    }

    /**
     * @inheritdoc
     * @param ResponseSchema|null $responseSchema
     * @return ResponseSchema
     */
    public function response(ResponseSchema $responseSchema = null): ResponseSchema
    {
        if(!is_null($responseSchema)){
            $this->responseSchema = $responseSchema;
        }

        return $this->responseSchema;
    }

    /**
     * @return ResponseSchema
     */
    public function getResponseSchema() : ResponseSchema
    {
        return $this->response();
    }
}
