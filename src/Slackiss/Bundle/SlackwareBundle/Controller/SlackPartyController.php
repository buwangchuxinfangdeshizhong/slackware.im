<?php

namespace Slackiss\Bundle\SlackwareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Slackiss\Bundle\SlackwareBundle\Entity\Member;
use Slackiss\Bundle\SlackwareBundle\Entity\Event;
use Slackiss\Bundle\SlackwareBundle\Form\EventType;


class SlackPartyController extends Controller
{
    /**
     * @Route("/slackparty/",name="slackparty")
     * @Template()
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_slackparty');
        $em = $this->getDoctrine()->getManager();
        $page = $request->query->get('page',1);
        $paginator = $this->get('knp_paginator');
        $query = $em->getRepository('SlackissSlackwareBundle:Event')
                    ->createQueryBuilder('m')
                    ->orderBy('m.id','desc')
                    ->getQuery();
        $events = $paginator->paginate($query,$page,4);
        $param['events']=$events;
        return $param;
    }
    
    /**
     * @Route("/member/slackparty/new",name="member_slackparty_new")
     * @Template()
     * @Method({"GET"})
     */
    public function newAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_slackparty');
        $event = new Event();
        $eventType = new EventType();
        $form = $this->createForm($eventType,$event,array(
            'action'=>$this->generateUrl('member_slackparty_create'),
            'method'=>'POST'
        ));
        $param['event']=$event;
        $param['form']=$form->createView();
        return $param;
    }

    /**
     * @Route("/member/slackparty/create",name="member_slackparty_create")
     * @Template("SlackissSlackwareBundle:SlackParty:new.html.twig")
     * @Method({"PUT","POST"})
     */
    public function createAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_slackparty');
        $current = $this->get('security.context')->getToken()->getUser();
        $member = $this->getDoctrine()->getManager()
                       ->getRepository('SlackissSlackwareBundle:Member')
                       ->find($current->getId());
        $event = new Event();
        $eventType = new EventType();
        $form = $this->createForm($eventType,$event,array(
            'action'=>$this->generateUrl('member_slackparty_create'),
            'method'=>'POST'
        ));
        $form->handleRequest($request);
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $event->setMember($member);
            $event->addPlayer($member);
            $em->persist($event);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success','SlackParty创建成功');
            return $this->redirect($this->generateUrl('slackparty_show',array('id'=>$event->getId())));
        }
        $param['event']=$event;
        $param['form']=$form->createView();
        return $param;        
    }

    /**
     * @Route("/slackparty/{id}",name="slackparty_show")
     * @Method({"GET"})
     * @Template()
     */
    public function showAction(Request $request,$id)
    {
        $param=array('nav_active'=>'nav_active_slackparty');
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('SlackissSlackwareBundle:Event');
        $event = $repo->find($id);
        if(!$event){
            throw $this->createNotFoundException("没有这个活动");
        }
        $param['event']=$event;
        $param['form']=$this->createFormBuilder([])
                            ->setAction($this->generateUrl('member_slackparty_join',array('id'=>$event->getId())))
                            ->setMethod('POST')
                            ->add('参加活动','submit')
                            ->getForm()->createView();
        return $param;
    }

    /**
     * @Route("/member/slackparty/join/{id}",name="member_slackparty_join")
     * @Method({"POST"})
     */
    public function joinAction(Request $request,$id)
    {
        $current = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('SlackissSlackwareBundle:Event')->find($id);
        if(!$event){
            throw $this->createNotFoundException('这个活动不存在');
        }
        $event->removePlayer($current);
        $event->addPlayer($current);
        $em->persist($event);
        $em->flush();
        $this->get('session')->getFlashBag()->add('success','参加活动');
        return $this->redirect($this->generateUrl('slackparty_show',array('id'=>$id)));
    }
}

