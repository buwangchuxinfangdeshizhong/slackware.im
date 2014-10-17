<?php

namespace Slackiss\Bundle\SlackwareBundle\Tests\Service;

use Slackiss\Bundle\SlackwareBundle\Entity\Member;
use Slackiss\Bundle\SlackwareBundle\Entity\Item;
use Slackiss\Bundle\SlackwareBundle\Entity\ItemCategory;
use Slackiss\Bundle\SlackwareBundle\Tests\SymfonyTestCase;

class ItemServiceTest extends SymfonyTestCase
{
    protected $itemService ;

    public function setUp()
    {
        parent::setUp();
        $this->itemService = $this->container->get('slackiss_slackware.item');
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testGetBuildCategory()
    {
        $this->assertTrue(true);
    }

    public function testGetTitle()
    {
        $path1 = '/栏目1/栏目2/标题';
        $title = $this->itemService->getTitle($path1);
        $this->assertEquals('标题',$title);
        $path2 = '/栏目1/栏目2/栏目3/栏目4/栏目5/标题';
        $title = $this->itemService->getTitle($path2);
        $this->assertEquals('标题',$title);
        $path3 = '/栏目1/栏目2/栏目3/栏目4/栏目5/栏目6/标题';
        $title = $this->itemService->getTitle($path3);
        $this->assertEquals('栏目6标题',$title);
    }
}