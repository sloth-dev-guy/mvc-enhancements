<?php

namespace SlothDevGuy\MVCEnhancements\DesignPatterns\Traits;

use SlothDevGuy\MVCEnhancements\Interfaces\Repository;

/**
 * Trait WithRepositories
 * @package SlothDevGuy\MVCEnhancements\Traits
 */
trait WithRepositories
{
    /**
     * @var array
     */
    protected array $repositories = [];

    /**
     * @param string $name
     * @param Repository|null $repository
     * @return Repository
     */
    public function repository(string $name, Repository $repository = null): Repository
    {
        if(!is_null($repository)){
            data_set($this->repositories, $name, $repository);
        }

        return data_get($this->repositories, $name);
    }


    /**
     * @inheritdoc
     * @return void
     */
    protected function onConstruct(): void
    {
        $this->setDefaultRepositories();
    }

    /**
     * @return static
     */
    protected function setDefaultRepositories(): static
    {
        collect(static::defaultRepositories())
            ->each(fn(Repository|string $repository, $name) => $this->repository($name, $this->resolveRepository($repository)));

        return $this;
    }

    /**
     * @param Repository|string $repository
     * @return Repository
     */
    protected function resolveRepository(Repository|string $repository) : Repository
    {
        return is_string($repository)? app($repository) : $repository;
    }
}
