<?php

namespace SlothDevGuy\MVCEnhancements\Services;

use Jenssegers\Mongodb\Eloquent\Model;

/**
 * Trait WithMongoDB
 * @package SlothDevGuy\MVCEnhancements\Services
 */
trait WithMongoDB
{
    /**
     * @var MongoDB
     */
    private MongoDB $mongoDB;

    /**
     * @param Model $model
     * @return static
     */
    public function buildMongoDB(Model $model) : static
    {
        $this->mongoDB = new MongoDB($model);

        return $this;
    }

    /**
     * @return MongoDB
     */
    public function mongoDB() : MongoDB
    {
        return $this->mongoDB;
    }
}
