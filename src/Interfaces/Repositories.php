<?php

namespace SlothDevGuy\MVCEnhancements\Interfaces;

/**
 * Interface Repositories
 * @package SlothDevGuy\MVCEnhancements\Interfaces
 */
interface Repositories
{
    /**
     * Gets or Sets a repository
     *
     * @param string $name
     * @param Repository|null $repository
     * @return Repository
     */
    public function repository(string $name, Repository $repository =  null) : Repository;

    /**
     * Returns the default repositories used by the instances of this class
     *
     * @return array
     */
    public static function defaultRepositories() : array;
}
