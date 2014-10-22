<?php

namespace Slackiss\Bundle\SlackwareBundle\Service;

use Symfony\Component\HttpKernel\Kernel;
use Slackiss\Bundle\SlackwareBundle\Entity\Item;
use Slackiss\Bundle\SlackwareBundle\Entity\ItemCategory;


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
        $topCategories = $this->getTopCategories();
        return $this->getCategoryArr($topCategories);
    }

    public function getCategoryArr($categories){
        $res = [];
        foreach($categories as $category){
            $subCategories = $this->getSubCategories($category);
            if(count($subCategories)===0){
                $subCategories = [];
            }else{
                $subCategories = $this->getCategoryArr($subCategories);
            }
            $res[] = ['cat'=>$category,'subCats'=>$subCategories];
        }
        return $res;
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
        $preArr = explode('/', $path);

        $count = count($preArr);
        if(empty($preArr[$count-1])){
            return false;
        }

        $arr = [];
        foreach($preArr as $a){
            if(!empty($a)){
                $arr[] = $a;
            }
        }
        $count = count($arr);

        if($count>7){
            $categoryArr = array_slice($arr,1,5);//no problem
        }else{
            if($count<=1){
                return false;
            }
            $categoryArr = array_slice($arr,0,$count-1);
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
        for($i=0;$i<count($arr); $i++){
            if(empty($arr[$i])){
                continue;
            }

            if(mb_strlen($arr[$i])>25){
                $arr[$i] = mb_substr($arr[$i],0,24);
            }

            $categoryUid = urlencode($arr[$i]);
            $categoryName = trim($arr[$i]);
            if($i==0){
                $cats = $this->getTopCategories();
                foreach($cats as $c){
                    if($c->getUid()===$categoryUid){
                        $category = $c;
                        break;
                    }
                }
                if(!$category){
                    $category = new ItemCategory();
                    $category->setUid($categoryUid);
                    $category->setName($categoryName);
                    $this->em->persist($category);
                    $this->em->flush();
                }
            }else{
                if($category!==false){
                    $subCategories = $this->getSubCategories($category);
                    $newCategory = false;
                    foreach($subCategories as $c){
                       if($c->getUid()===$categoryUid){
                           $newCategory = $c;
                           $category = $newCategory;
                           break;
                       }
                    }
                    if(!$newCategory){
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
        return $category;
    }

    public function createItem($item)
    {
        $path = $item->getPath();
        $categoryArr = $this->buildCategory($path);
        if(false!==$categoryArr){
            $category = $this->getCategoryFromArr($categoryArr);
            if(false===$category){
                return false;
            }
            $item->setCategory($category);
            $title = $this->getTitle($path);
            $item->setTitle($title);
            $item->setVersion(1);
            $item->setLast(true);
            $item->setTop(null);
            $this->em->persist($item);
            $this->em->flush();
        }
        return $item;
    }

    public function updateItem($item)
    {
        $path = $item->getPath();
        $newItem = false;
        $categoryArr = $this->buildCategory($path);
        if(false!==$categoryArr){
            $category = $this->getCategoryFromArr($categoryArr);
            if(false===$category){
                return false;
            }
            $newItem = new Item();
            $title = $this->getTitle($path);
            $newItem->setCategory($category);
            $newItem->setTitle($title);
            $newItem->setPath($path);
            $newItem->setContent($item->getContent());
            $newItem->setVersion(($item->getVersion()+1));
            $newItem->setLast(true);
            $newItem->setChangelog($item->getChangeLog());
            $newItem->setMember($item->getMember());
            $item = $this->em->getRepository('SlackissSlackwareBundle:Item')->find($item->getId());
            $item->setLast(false);
            $this->em->persist($item);
            $newItem->setTop($item);
            $this->em->persist($newItem);
            $this->em->flush();
        }
        return $newItem;
    }
}
