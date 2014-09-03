<?php

namespace Slackiss\Bundle\SlackwareBundle\Tests\Controller;

use Slackiss\Bundle\SlackwareBundle\Tests\SymfonyTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends SymfonyTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }
    public function testIndex()
    {
        $this->assertTrue(true);
    }
}
