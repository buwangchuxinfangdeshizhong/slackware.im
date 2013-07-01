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
use Slackiss\Bundle\SlackwareBundle\Entity\SlackDesk;
use Slackiss\Bundle\SlackwareBundle\Form\MemberSlackDeskType;

class SlackDeskController extends Controller
{
    /**
     * @Route("/slackdesk/",name="slackdesk")
     * @Template()
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_slackdesk');
        $em = $this->getDoctrine()->getManager();
        $page = $request->query->get('page',1);
        $paginator = $this->get('knp_paginator');
        $query = $em->getRepository('SlackissSlackwareBundle:SlackDesk')
                    ->createQueryBuilder('m')
                    ->orderBy('m.id','desc')
                    ->getQuery();
        $members = $paginator->paginate($query,$page,30);
        $param['slackdesks']=$members;
        return $param;
    }
    
    /**
     * @Route("/member/slackdesk/new",name="member_slackdesk_new")
     * @Template()
     * @Method({"GET"})
     */
    public function newAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_slackdesk');
        $slackDesk = new SlackDesk();
        $slackDeskType = new MemberSlackDeskType();
        $form = $this->createForm($slackDeskType,$slackDesk,array(
            'action'=>$this->generateUrl('member_slackdesk_create'),
            'method'=>'POST'
        ));
        $param['slackdesk']=$slackDesk;
        $param['form']=$form->createView();
        return $param;
    }

    /**
     * @Route("/member/slackdesk/create",name="member_slackdesk_create")
     * @Template("SlackissSlackwareBundle:SlackDesk:new.html.twig")
     * @Method({"PUT","POST"})
     */
    public function createAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_slackdesk');
        $current = $this->get('security.context')->getToken()->getUser();
        $member = $this->getDoctrine()->getManager()
                       ->getRepository('SlackissSlackwareBundle:Member')
                       ->find($current->getId());
        $slackDesk = new SlackDesk();
        $slackDeskType = new MemberSlackDeskType();
        $form = $this->createForm($slackDeskType,$slackDesk,array(
            'action'=>$this->generateUrl('member_slackdesk_create'),
            'method'=>'POST'
        ));
        $form->handleRequest($request);
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $slackDesk->setMember($member);
            $em->persist($slackDesk);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success','SlackDesk创建成功');
            return $this->redirect($this->generateUrl('slackdesk_show',array('id'=>$slackDesk->getId())));
        }
        $param['slackdesk']=$slackdesk;
        $param['form']=$form->createView();
        return $param;        
    }

    /**
     * @Route("/slackdesk/{id}",name="slackdesk_show")
     * @Method({"GET"})
     * @Template()
     */
    public function showAction(Request $request,$id)
    {
        $param=array('nav_active'=>'nav_active_slackdesk');
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('SlackissSlackwareBundle:SlackDesk');
        $slackdesk = $repo->find($id);
        if(!$slackdesk){
            throw $this->createNotFoundException("没有这个Slacker档案");
        }

        $param['slackdesk']=$slackdesk;
        return $param;
    }
}

