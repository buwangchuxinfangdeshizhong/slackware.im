<?php

namespace Slackiss\Bundle\SlackwareBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Slackiss\Bundle\SlackwareBundle\Entity\Post;
use Slackiss\Bundle\SlackwareBundle\Entity\PostCategory;

class PostCategoryMigrationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('slackiss:slackware:postcategory:migration')
            ->setDescription('Migration');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $defaultCategory = $this->getDefaultCategory();
        $posts = $em->getRepository('SlackissSlackwareBundle:Post')-findAll();
        foreach($posts as $post){
            if(null==$post->getCategory()){
                $post->setCategory($defaultCategory);
                $em->persist($post);
                $em->flush();
            }
        }
        $output->writeln('success');
    }

    protected function initCategories()
    {
        $container = $this->getContainer();
        $categories = [
            ['name'=>'桌面用户体验与中文','uid'=>'zhuomianyonghutiyanyuzhongwen',
             'description'=>'讨论Slackware桌面应用的相关话题',
             'sequence'=>100],
            ['name'=>'系统启动安装与升级','uid'=>'xitongqidonganzhuangyushengji',
             'description'=>'讨论Slackware系统启动，安装与升级相关的问题',
             'sequence'=>200],
            ['name'=>'Linux基础与讨论','uid'=>'linuxjichuyutaolun',
             'description'=>'讨论Linux的基础知识',
             'sequence'=>300],
            ['name'=>'常用硬件与周边设备','uid'=>'changyongyingjianyuzhoubianshebei',
             'description'=>'各种运行Linux的常用硬件与周边设备',
             'sequence'=>400],
            ['name'=>'Linux内核与驱动','uid'=>'linuxneiheyuqudong',
             'description'=>'讨论Linux内核技术与驱动',
             'sequence'=>500],
            ['name'=>'Shell与脚本编程','uid'=>'shellyujiaobenbiancheng',
             'description'=>'各种Shell与系统管理相关的脚本编程',
             'sequence'=>600],
            ['name'=>'Linux软件开发','uid'=>'linuxruanjiankaifa',
             'description'=>'开发各种可以运行在Linux下面的应用软件',
             'sequence'=>700],
            ['name'=>'BSD专题讨论','uid'=>'bsdzhuantitaolun',
             'description'=>'在这里可以讨论与BSD相关的各种话题',
             'sequence'=>800],
            ['name'=>'SlackDEV专区','uid'=>'slackdevzhuanqu',
             'description'=>'试图将Slackware打造成最适合开发者使用的平台',
             'sequence'=>900],
            ['name'=>'大话闲聊','uid'=>'dahuaxianliao',
             'description'=>'欢迎闲聊',
             'sequence'=>1000],
            ['name'=>'站务专版','uid'=>'zhanwuzhuanban',
             'description'=>'沟通关于站务的问题',
             'sequence'=>1100],
            ['name'=>'归档区域','uid'=>'archive',
             'description'=>'有一些不适合公开或者话题不合适的帖子将被移动到这里',
             'sequence'=>50000]
        ];

        $em = $container->get('doctrine.orm.entity_manager');
        $repo = $em->getRepository('SlackissSlackwareBundle:PostCategory');
        foreach($categories as $category){
            if(!$repo->findOneBy(['uid'=>$category['uid']])){
                $c = new PostCategory();
                $c->setName($category['name']);
                $c->setUid($category['uid']);
                $c->setSequence($category['sequence']);
                $c->setDescription($category['description']);
                $em->persist($c);
                $em->flush();
            }
        }
    }

    protected function getDefaultCategory()
    {
        $container = $this->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $this->initCategories();
        $repo = $em->getRepository('SlackissSlackwareBundle:PostCategory');
        return $repo->findOneBy(['uid'=>'dahuaxianliao']);
    }
}