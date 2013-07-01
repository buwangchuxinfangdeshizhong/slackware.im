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
use Slackiss\Bundle\SlackwareBundle\Form\SlackerType;

class SlackerController extends Controller
{
    /**
     * @Route("/slacker/",name="slacker")
     * @Template()
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_slacker');
        $em = $this->getDoctrine()->getManager();
        $page = $request->query->get('page',1);
        $paginator = $this->get('knp_paginator');
        $query = $em->getRepository('SlackissSlackwareBundle:Member')
                    ->createQueryBuilder('m')
                    ->where('m.nickname is not null')
                    ->orderBy('m.id','desc')
                    ->getQuery();
        $members = $paginator->paginate($query,$page,30);
        $param['members']=$members;
        return $param;
    }
    
    /**
     * @Route("/member/slacker/edit",name="member_slacker_edit")
     * @Template()
     * @Method({"GET"})
     */
    public function editAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_slacker');
        $current = $this->get('security.context')->getToken()->getUser();
        $member = $this->getDoctrine()->getManager()
                       ->getRepository('SlackissSlackwareBundle:Member')
                       ->find($current->getId());
        if(!$member->getNickname()){
            $member->setNickname($member->getUsername());
        }
        $slackerType = new SlackerType();
        $form = $this->createForm($slackerType,$member,array(
            'action'=>$this->generateUrl('member_slacker_update'),
            'method'=>'POST'
        ));
        $param['member']=$member;
        $param['form']=$form->createView();
        return $param;
    }

    /**
     * @Route("/member/slacker/update",name="member_slacker_update")
     * @Template("SlackissSlackwareBundle:Slacker:edit.html.twig")
     * @Method({"PUT","POST"})
     */
    public function updateAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_slacker');
        $current = $this->get('security.context')->getToken()->getUser();
        $member = $this->getDoctrine()->getManager()
                       ->getRepository('SlackissSlackwareBundle:Member')
                       ->find($current->getId());
        $slackerType = new SlackerType();
        $form = $this->createForm($slackerType,$member,array(
            'action'=>$this->generateUrl('member_slacker_update'),
            'method'=>'POST'
        ));
        $form->handleRequest($request);
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($member);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success','Slacker档案保存成功');
            return $this->redirect($this->generateUrl('slacker_show',array('id'=>$member->getId())));
        }
        $param['member']=$member;
        $param['form']=$form->createView();
        return $param;        
    }

    /**
     * @Route("/slacker/{id}",name="slacker_show")
     * @Method({"GET"})
     * @Template()
     */
    public function showAction(Request $request,$id)
    {
        $param=array('nav_active'=>'nav_active_slacker');
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('SlackissSlackwareBundle:Member');
        $member = $repo->find($id);
        if(!$member||!$member->getNickname()){
            throw $this->createNotFoundException("没有这个Slacker档案");
        }

        $param['member']=$member;
        return $param;
    }
}

