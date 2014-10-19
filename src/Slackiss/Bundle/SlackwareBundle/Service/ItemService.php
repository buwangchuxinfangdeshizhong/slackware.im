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

    public function getCategoryTree()
    {

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
        $arr = explode('/', $path);
        $count = count($arr);
        if($count>7){
            $categoryArr = array_slice($arr,1,5);
        }else{
            if($count<=2){
                return false;
            }
            if(empty($arr[2])){
                return false;
            }
            $categoryArr = array_slice($arr,-2,$count-2);
        }
        return $categoryArr;
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

    public function getCategoryFromArr($arr)
    {
        $category     = false;
        for($i=1;$i<count($arr); $i++){
            if(empty($arr[$i])){
                continue;
            }

            if(mb_strlen($arr[$i])>25){
                $arr[$i] = $mb_substr($arr[$i],0,24);
            }

            $categoryUid = urlencode($arr[$i]);
            $categoryName = trim($arr[$i]);


            if($i==1){
                $cats = $this->getTopCategories();
                foreach($cats as $c){
                    if($c->getUid()===$categoryUid){
                        $category = $c;
                    }else{
                        $category = new ItemCategory();
                        $categoty->setUid($categoryUid);
                        $categOry->setName($categoryName);
                        $this->em->persist($category);
                        $this->em->flush();
                    }
                }
            }else{
                if($category!==false){
                    $subCategories = $this->getSubCategories($category);
                    foreach($subCategories as $c){
                       if($c->getUid()===$categoryUid){
                           $category = $c;
                       }else{
                           $newCategory = new ItemCategory();
                           $newCategory->setUid($categoryUid);
                           $newCategory->setName($categoryName);
                           $newCategory->setParent($category);
                           $this->em->persist($newCategory);
                           $this->em->flush();
                           $category = $newCategory;
                       }
                    }
                }
            }
        }
        return $catgory;
    }

    public function createItem(p$item)
    {
        $categoryArr = $this->buildCategory($path);
        if($categoryArr){
            $category = $this->getCategoryFromArr($categoryArr);
            $item->setCategory($category);
            $title    = $this->getTitle($path);
            $item->setTitle($title);
            $item->setVersion(1);
            $item->setLast(true);
            $item->setTop(null);
            $this->em->persist($item);
            $this->em->flush();
        }
        return $item;
    }

    public function editItem($path, $item, $newItem)
    {

    }
}
