<?php

namespace Slackiss\Bundle\SlackwareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Slackiss\Bundle\SlackwareBundle\Form\ItemType;
use Slackiss\Bundle\SlackwareBundle\Entity\Item;
use Slackiss\Bundle\SlackwareBundle\Entity\ItemCategory;

/**
 * @Route("/knowledge")
 */
class KnowledgeController extends Controller
{

    protected function getParam()
    {
        $param = [];
        $param['tree'] = $this->get('slackiss_slackware.item')->getCategoryTree();
        return $param;
    }

    /**
     * @Route("/",name="knowledge")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $param = $this->getParam();
        return $param;
    }

    /**
     * @Route("/member/new",name="knowledge_new")
     * @Method({"GET"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $param = $this->getParam();
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();
        $item = new Item();
        $item->setMember($current);
        $form = $this->createCreateForm($item);
        $param['form'] = $form->createView();
        return $param;
    }

    /**
     * @Route("/member/create",name="knowledge_create")
     * @Method({"POST"})
     * @Template("SlackissSlackwareBundle:Knowledge:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $param = $this->getParam();
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();
        $item = new Item();
        $item->setMember($current);
        $form = $this->createCreateForm($item);
        $form->handleRequest($request);
        if($form->isValid()){
            $itemService = $this->get('slackiss_slackware.item');
            $item = $itemService->createItem($item);
        }
        if($item){
            return $this->redirect($this->generateUrl('knowledge_show',['id'=>$item->getId()]));
        }else{
            $param['form'] = $form->createView();
            return $param;
        }
    }

    public function createCreateForm($item)
    {
        $type = new ItemType();
        $form = $this->createForm($type, $item, [
            'method'=>'POST',
            'action'=>$this->generateUrl('knowledge_create')
        ]);
        $form->add('submit','submit',[
            'label' => '保存',
        ]);
        return $form;
    }

    /**
     * @Route("/show/{id}",name="knowledge_show")
     * @Method({"GET"})
     * @Template()
     */
    public function showAction(Request $request,$id)
    {
        $param = $this->getParam();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SlackissSlackwareBundle:Item')->find($id);
        $param['entity'] = $entity;
        return $param;
    }

        /**
         * @Route("/list/{id}",name="knowledge_list")
         * @Method({"GET"})
         * @Template()
         */
    public function listAction(Request $request,$id)
    {
        $param =  $this->getParam();
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('SlackissSlackwareBundle:ItemCategory')
                       ->find($id);
        if(!$category){
            throw $this->createNotFoundException('没找到这个知识库分类');
        }
        $repo = $em->getRepository('SlackissSlackwareBundle:Item');
        $page = $request->query->get('page',1);
        $query = $repo->createQueryBuilder('i')
                      ->orderBy('i.id','desc')
                      ->where('i.category = :category and i.last = true')
                      ->setParameters([':category'=>$id])
                      ->getQuery();
        $param['category'] = $category;
        $param['entities'] = $this->get('knp_paginator')->paginate($query,$page,50);
        return $param;
    }


    /**
     * @Route("/member/edit/{id}",name="knowledge_edit")
     * @Method({"GET"})
     * @Template()
     */
    public function editAction(Request $request,$id)
    {
        $param = $this->getParam();
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();
        $item = $em->getRepository('SlackissSlackwareBundle:Item')->find($id);
        if(!$item){
            $item = new Item();
        }
        $item->setMember($current);
        $item->setChangelog('');
        $form = $this->createEditForm($item);
        $param['form'] = $form->createView();
        return $param;
    }

    /**
     * @Route("/member/update/{id}",name="knowledge_update")
     * @Method({"POST"})
     * @Template("SlackissSlackwareBundle:Knowledge:edit.html.twig")
     */
    public function updateAction(Request $request,$id)
    {
        $param = $this->getParam();
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();
        $item = $em->getRepository('SlackissSlackwareBundle:Item')->find($id);
        if(!$item){
            $item = new Item();
        }
        $item->setMember($current);
        $form = $this->createEditForm($item);
        $form->handleRequest($request);
        if($form->isValid()){
            $itemService = $this->get('slackiss_slackware.item');
            $item = $itemService->updateItem($item);
        }
        if($item){
            return $this->redirect($this->generateUrl('knowledge_show',['id'=>$item->getId()]));
        }else{
            $param['form'] = $form->createView();
            return $param;
        }
    }

    public function createEditForm($item)
    {
        $type = new ItemType();
        $form = $this->createForm($type, $item, [
            'method'=>'POST',
            'action'=>$this->generateUrl('knowledge_update',['id'=>$item->getId()])
        ]);
        $form->add('submit','submit',[
            'label' => '保存',
        ]);
        return $form;
    }

}
