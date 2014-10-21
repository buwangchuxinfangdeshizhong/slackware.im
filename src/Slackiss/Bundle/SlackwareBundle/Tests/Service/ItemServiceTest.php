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

    public function testExplode()
    {
        $this->assertEquals(2,count(explode('/','/ok')));
        $this->assertEquals(3,count(explode('/','/ok/')));
        $this->assertTrue(true);
    }

    public function testBuildCategory()
    {
        $path1 = '/';
        $arr = $this->itemService->buildCategory($path1);
        $this->assertFalse($arr);
        $path2 = '/ok/';
        $arr = $this->itemService->buildCategory($path2);
        $this->assertFalse($arr);
        $path3 = '/ok1/ok';
        $arr = $this->itemService->buildCategory($path3);
        $this->assertEquals('ok1',implode('',$arr));
        $path4 = '/ok/ok/ok/ok/ok/ok/ok/ok';
        $arr = $this->itemService->buildCategory($path4);
        $this->assertEquals('okokokokok',implode('',$arr));
        $path4 = '/ok/ok/ok/ok/ok/ok/ok/ok';
        $arr = $this->itemService->buildCategory($path4);
        $this->assertEquals('okokokokok',implode('',$arr));
        $path5 = '/ok/ok/ok/ok';
        $arr = $this->itemService->buildCategory($path5);
        $this->assertEquals('okokok',implode('',$arr));
        $path6 = '/ok/ok/ok/ok/ok';
        $arr = $this->itemService->buildCategory($path6);
        $this->assertEquals('okokokok',implode('',$arr));
        $path7 = '/';
        $arr = $this->itemService->buildCategory($path7);
        $this->assertFalse($arr);
        $path8 = '//';
        $arr = $this->itemService->buildCategory($path8);
        $this->assertFalse($arr);

        $path9 = '/ok/ok/';
        $arr = $this->itemService->buildCategory($path9);
        $this->assertFalse($arr);
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