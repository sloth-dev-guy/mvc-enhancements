<?php

namespace Tests;

/**
 * Class SetupTest
 * @package Tests
 */
class SetupTest extends TestCase
{
    /**
     * Test all the configurations and database setup.
     *
     * @return void
     */
    public function testApp() : void
    {
        $this->assertNotNull(app());
    }
}
