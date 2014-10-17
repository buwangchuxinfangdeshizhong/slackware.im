<?php

namespace Slackiss\Bundle\SlackwareBundle\Service;

use Symfony\Component\HttpKernel\Kernel;

class ItemService {

    protected $em;
    protected $paginator;

    public function __construct($em,$paginator)
    {
        $this->em        = $em;
        $this->paginator = $paginator;
    }

    public function getTopCategories()
    {
        $repo = $this->em->getRepository('SlackissSlackwareBundle:ItemCategory');
        $query = $repo->createQueryBuilder('c')
                      ->where('c.status = true and c.enabled = true and c.parent is null')
                      ->orderBy('c.id','asc')
                      ->getQuery();
        return $query->getResult();
    }

    public function getSubCategories($category)
    {
        $repo = $this->em->getRepository('SlackissSlackwareBundle:ItemCategory');
        $query = $repo->createQueryBuilder('c')
                      ->where('c.status = true and c.enabled = true and c.parent = :parent')
                      ->orderBy('c.id','asc')
                      ->setParameters([':parent'=>$category->getId()])
                      ->getQuery();
        return $query->getResult();
    }

    public function buildCategory($path)
    {
        return null;
    }

    public function getTitle($path)
    {
        $arr = explode('/', $path);
        $count = count($arr);
        if($count>7){
            $titleArr = array_slice($arr,6,$count-6);
            $title = implode('',$titleArr);
        }else{
            $title = $arr[$count-1];
        }
        return $title;
    }

    public function createItem(p$item)
    {
        $category = $this->buildCategory($path);
        $title    = $this->getTitle($path);
        $item->setCategory($category);
        $item->setTitle($title);
        $item->setVersion(1);
        $item->setLast(true);
        $item->setTop(null);
        $this->em->persist($item);
        $this->em->flush();
        return $item;
    }

    public function editItem($path, $item, $newItem)
    {

    }
}
