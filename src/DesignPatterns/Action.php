<?php

namespace SlothDevGuy\MVCEnhancements\DesignPatterns;

use SlothDevGuy\MVCEnhancements\DesignPatterns\Traits\DefaultResponseSchema;
use SlothDevGuy\MVCEnhancements\Interfaces\Command;
use SlothDevGuy\MVCEnhancements\Interfaces\Request;
use SlothDevGuy\MVCEnhancements\Interfaces\ResponseSchema;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseAction
 * @package SlothDevGuy\MVCEnhancements
 */
abstract class Action implements Command
{
    use DefaultResponseSchema;

    /**
     * @var ResponseSchema|Response
     */
    protected ResponseSchema|Response $responseSchema;

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
     * @param ResponseSchema|Response|null $responseSchema
     * @return ResponseSchema|Response
     */
    public function response(ResponseSchema|Response $responseSchema = null): ResponseSchema|Response
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
