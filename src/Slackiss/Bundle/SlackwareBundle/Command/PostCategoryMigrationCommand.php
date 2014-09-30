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
        $posts = $em->getRepository('SlackissSlackwareBundle:Post');
        foreach($posts as $post){
            if(!$post->getCategory()){
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
            ['name'=>'桌面、用户体验与中文','uid'=>'zhuomianyonghutiyanyuzhongwen','sequence'=>100],
            ['name'=>'系统启动、安装与升级','uid'=>'xitongqidonganzhuangyushengji','sequence'=>200],
            ['name'=>'Linux基础与讨论','uid'=>'linuxjichuyutaolun','sequence'=>300],
            ['name'=>'常用硬件与周边设备','uid'=>'changyongyingjianyuzhoubianshebei','sequence'=>400],
            ['name'=>'Linux内核与驱动','uid'=>'linuxneiheyuqudong','sequence'=>500],
            ['name'=>'Shell与脚本编程','uid'=>'shellyujiaobenbiancheng','sequence'=>600],
            ['name'=>'Linux软件开发','uid'=>'linuxruanjiankaifa','sequence'=>700],
            ['name'=>'BSD专题讨论','uid'=>'bsdzhuantitaolun','sequence'=>800],
            ['name'=>'SlackDEV专区','uid'=>'slackdevzhuanqu','sequence'=>900],
            ['name'=>'大话闲聊','uid'=>'dahuaxianliao','sequence'=>1000],
            ['name'=>'站务专版','uid'=>'zhanwuzhuanban','sequence'=>1100],
            ['name'=>'归档区域','uid'=>'archive','sequence'=>50000]
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