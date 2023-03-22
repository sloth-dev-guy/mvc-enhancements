<?php

namespace SlothDevGuy\MVCEnhancements\Services;

use Jenssegers\Mongodb\Connection;
use Jenssegers\Mongodb\Eloquent\Model;
use MongoDB\Collection;
use MongoDB\Database;

/**
 * Class MongoDBConnectionServices
 * @package CTDesarrollo\PerformanceMonitor\Models\Services
 *
 * A simple service class that wraps and expose the abilities of the mongodb driver, it needs a mongodb model
 */
class MongoDB
{
    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @param Model $model
     * @noinspection PhpFieldAssignmentTypeMismatchInspection
     */
    public function __construct(
        private readonly Model $model
    )
    {
        $this->connection = $this->model->getConnection();
    }

    /**
     * Current mongodb connection
     *
     * @return Database
     */
    public function mongoDB(): Database
    {
        return $this->connection->getMongoDB();
    }

    /**
     * Current mongodb collection
     *
     * @return Collection
     */
    public function collection(): Collection
    {
        return $this->mongoDB()
            ->selectCollection($this->model->getTable());
    }
}
