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

    /**
     * @Route("/",name="knowledge")
     * @Method({"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $param =  array();
        return $param;
    }

    /**
     * @Route("/member/new",name="knowledge_new")
     * @Method({"GET"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $param =  array();
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
        $param =  array();
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
        $param =  array();
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();
        $entity = $em->getRepository('SlackissSlackwareBundle:Item')->find($id);
        $param['entity'] = $entity;
        return $param;
    }

}
