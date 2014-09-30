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
use Slackiss\Bundle\SlackwareBundle\Entity\DeskComment;
use Slackiss\Bundle\SlackwareBundle\Form\MemberSlackDeskType;
use Slackiss\Bundle\SlackwareBundle\Form\MemberDeskCommentType;


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
        $slackDesk = $repo->find($id);
        if(!$slackDesk){
            throw $this->createNotFoundException("没有这个Slacker档案");
        }
        $param['slackdesk']=$slackDesk;
        
        $comment = new DeskComment();
        $param['form'] = $this->getCommentForm($slackDesk,$comment)->createView();

        $page = $request->query->get('page',1);
        $param['comments'] = $this->getComments($slackDesk,$page);
        return $param;
    }

    /**
     * @Route("/member/slackdesk/comment/create/{id}",name="member_slackdesk_comment_create")
     * @Method({"POST"})
     * @Template("SlackissSlackwareBundle:SlackDesk:show.html.twig")
     */
    public function commentCreateAction(Request $request, $id)
    {
        $param=array('nav_active'=>'nav_active_slackdesk');
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('SlackissSlackwareBundle:SlackDesk');
        $slackDesk = $repo->find($id);
        if(!$slackDesk){
            throw $this->createNotFoundException("没有这个Slacker档案");
        }
        $param['slackdesk']=$slackDesk;
     
        $comment = new DeskComment();   
        $form = $this->getCommentForm($slackDesk,$comment);
        $form->handleRequest($request);
        if($form->isValid()){
            $current = $this->get('security.context')->getToken()->getUser();
            $comment->setSlackDesk($slackDesk);
            $comment->setMember($current);
            $em->persist($comment);
            $em->flush();
            return $this->redirect($this->generateUrl('slackdesk_show',array('id'=>$slackDesk->getId())));
        }
        $param['form'] = $form->createView();
        $page = $request->query->get('page',1);
        $param['comments'] = $this->getComments($slackDesk,$page);
        return $param;
    }

    /**
     *
     * @Route("/manager/deskcomment/delete/{id}",name="deskcomment_delete")
     * @Method({"GET","DELETE"})
     *
     */
    public function deleteDeskCommentAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $deskComment = $em->getRepository('SlackissSlackwareBundle:DeskComment')->find($id);
        if($deskComment){
            $deskId = $deskComment->getSlackDesk()->getId();
            $em->remove($deskComment);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success','删除成功');
            return $this->redirect($this->generateUrl('slackdesk_show',array('id'=>$deskId)));
        }
        return $this->redirect($this->generateUrl('slackdesk'));
    }


    private function getCommentForm($slackDesk,$comment)
    {
        $commentType = new MemberDeskCommentType();
        $form = $this->createForm($commentType,$comment,array(
            'action'=>$this->generateUrl('member_slackdesk_comment_create',array(
                'id'=>$slackDesk->getId()
            )),
            'method'=>'POST'
        ));
        return $form;
    }

    private function getComments($slackDesk,$page)
    {
        $query = $this->getDoctrine()->getManager()
                      ->getRepository("SlackissSlackwareBundle:DeskComment")
                      ->createQueryBuilder("c")
                      ->where("c.slackDesk = :slackdesk")
                      ->setParameters(array(':slackdesk'=>$slackDesk->getId()))
                      ->orderBy('c.id','asc')
                      ->getQuery();
        $comments = $this->get('knp_paginator')->paginate($query,$page,10);
        return $comments;
    }
}

