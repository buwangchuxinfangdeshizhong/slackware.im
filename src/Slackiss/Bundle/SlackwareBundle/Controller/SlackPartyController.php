<?php

namespace Slackiss\Bundle\SlackwareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Slackiss\Bundle\SlackwareBundle\Entity\Member;
use Slackiss\Bundle\SlackwareBundle\Entity\Event;
use Slackiss\Bundle\SlackwareBundle\Entity\EventComment;
use Slackiss\Bundle\SlackwareBundle\Entity\EventPicture;
use Slackiss\Bundle\SlackwareBundle\Form\EventType;
use Slackiss\Bundle\SlackwareBundle\Form\EventCommentType;
use Slackiss\Bundle\SlackwareBundle\Form\EventPictureType;


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
     * @Route("/member/slackparty/appendpage/{id}",name="member_slackparty_append_page")
     * @Method({"GET"})
     * @Template()
     */
    public function appendPageAction(Request $request,$id)
    {
        $param=array('nav_active'=>'nav_active_slackparty');
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();
        $repo = $em->getRepository('SlackissSlackwareBundle:Event');
        $event = $repo->find($id);
        if(!$event||$current->getId()!==$event->getMember()->getId()){
            throw $this->createNotFoundException('没找到这个活动');
        }
        if(!$event->isExpired()){
            throw $this->createNotFoundException('活动停止报名后才可以做补充');
        }
        $param['event'] = $event;
        return $param;
    }

    /**
     * @Route("/member/slackparty/append/{id}",name="member_slackparty_append")
     * @Method({"POST"})
     * @Template("SlackissSlackwareBundle:SlackParty:appendPage.html.twig")
     */
    public function appendAction(Request $request,$id)
    {
        $param =  array();
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();
        $repo = $em->getRepository('SlackissSlackwareBundle:Event');
        $event = $repo->find($id);
        if(!$event||$current->getId()!==$event->getMember()->getId()){
            throw $this->createNotFoundException('没找到这个活动');
        }
        if(!$event->isExpired()){
            throw $this->createNotFoundException('活动停止报名后才可以做补充');
        }
        $append = $request->request->get('append');
        if($append){
            if(mb_strlen($append)>5000){
                $append = mb_substr($append,0,5000);
            }
            $event->setAppend($append);
            $em->persist($event);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success','保存成功');
            return $this->redirect($this->generateUrl('slackparty_show',['id'=>$id]));
        }
        $param['event'] = $event;
        return $param;
    }

    /**
     * @Route("/member/slackparty/edit/{id}",name="member_slackparty_edit")
     * @Method({"GET"})
     * @Template()
     */
    public function editAction(Request $request,$id)
    {
        $param=array('nav_active'=>'nav_active_slackparty');
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();
        $repo = $em->getRepository('SlackissSlackwareBundle:Event');
        $event = $repo->find($id);
        if(!$event||$current->getId()!==$event->getMember()->getId()){
            throw $this->createNotFoundException('没找到这个活动');
        }
        if($event->isExpired()){
            throw $this->createNotFoundException('找不到这个页面,可能当前活动已经超过可编辑日期');
        }
        $eventType = new EventType(true);
        $form = $this->createForm($eventType,$event,[
            'action'=>$this->generateUrl('member_slackparty_update',['id'=>$id]),
            'method'=>'POST'
        ]);
        $param['event'] = $event;
        $param['form'] = $form->createView();
        return $param;
    }

    /**
     * @Route("/member/slackparty/update/{id}",name="member_slackparty_update")
     * @Method({"PUT","POST"})
     * @Template("SlackissSlackwareBundle:SlackParty:edit.html.twig")
     */
    public function updateAction(Request $request,$id)
    {
        $param=array('nav_active'=>'nav_active_slackparty');
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();
        $repo = $em->getRepository('SlackissSlackwareBundle:Event');
        $event = $repo->find($id);
        if(!$event||$current->getId()!==$event->getMember()->getId()){
            throw $this->createNotFoundException('没找到这个活动');
        }
        if($event->isExpired()){
            throw $this->createNotFoundException('找不到这个页面,可能当前活动已经超过可编辑日期');
        }
        $eventType = new EventType(true);
        $form = $this->createForm($eventType,$event,[
            'action'=>$this->generateUrl('member_slackparty_update',['id'=>$id]),
            'method'=>'POST'
        ]);
        $form->handleRequest($request);
        if($form->isValid()){
            $em->persist($event);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success','保存成功');
            return $this->redirect($this->generateUrl('slackparty_show',['id'=>$id]));
        }
        $param['event'] = $event;
        $param['form'] = $form->createView();
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
        $now = new \DateTime();
        $param['images'] = $this->getEventImages($event);
        $page  =  $request->query->get('page',1);
        $limit = 50;
        $param['comments'] = $this->getEventComments($event, $page, $limit);
        if($now<$event->getLastApplyDate()){
            $param['form']=$this->createFormBuilder([])
                                ->setAction($this->generateUrl('member_slackparty_join',array('id'=>$event->getId())))
                                ->setMethod('POST')
                                ->add('报名活动','submit')
                                ->getForm()->createView();
        }else{
            $param['form']=$this->createFormBuilder([])
                                ->setAction('#')
                                ->setMethod('GET')
                                ->add('报名已截止','submit')
                                ->getForm()->createView();
        }
        $comment = new EventComment();
        $comment->setEvent($event);
        $param['commentForm'] = $this->getCommentForm($comment)->createView();
        return $param;
    }

    protected function getCommentForm($comment)
    {
        $type = new EventCommentType();
        $form = $this->createForm($type, $comment,[
            'method'=>'POST',
            'action'=>$this->generateUrl('member_event_comment',['id'=>$comment->getEvent()->getId()])
        ]);
        return $form;
    }

    /**
     * @Route("/member/event/comment/{id}",name="member_event_comment")
     * @Method({"POST"})
     */
    public function eventCommentAction(Request $request,$id)
    {
        $param =  array();
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('SlackissSlackwareBundle:Event')->find($id);
        $current = $this->get('security.context')->getToken()->getUser();
        if(!$event){
            throw $this->createNotFoundException('没找到这个SlackParty');
        }
        $comment = new EventComment();
        $comment->setEvent($event);
        $comment->setMember($current);
        $form = $this->getCommentForm($comment);
        $form->handleRequest($request);
        if($form->isValid()){
            $em->persist($comment);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success','评论成功');
        }else{
            $this->get('session')->getFlashBag()->add('danger','请输入评论内容');
        }
        return $this->redirect($this->generateUrl('slackparty_show',['id'=>$id]));
    }

    protected function getEventComments($event, $page, $limit)
    {
        $em   = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('SlackissSlackwareBundle:EventComment');
        $query = $repo->createQueryBuilder('c')
                      ->where('c.event = :event and c.status = true and c.enabled = true')
                      ->setParameters([':event'=>$event->getId()])
                      ->getQuery();
        return $this->get('knp_paginator')->paginate($query, $page, $limit);
    }

    protected function getEventImages($event)
    {
        return $this->getDoctrine()->getManager()
                    ->getRepository('SlackissSlackwareBundle:EventPicture')
                    ->findBy(['event'=>$event->getId()]);
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
        $now = new \DateTime();
        if($now<$event->getLastApplyDate()){
            $event->removePlayer($current);
            $event->addPlayer($current);
            $em->persist($event);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success','参加活动');
        }
        return $this->redirect($this->generateUrl('slackparty_show',array('id'=>$id)));
    }

    /**
     * @Route("/member/slackparty/my",name="member_slackparty_my")
     * @Method({"GET"})
     * @Template()
     */
    public function mySlackpartyAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_slackparty');
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();
        $repo = $em->getRepository('SlackissSlackwareBundle:Event');
        $query = $repo->createQueryBuilder('e')
                      ->where('e.member = :member')
                      ->orderBy('e.id','desc')
                      ->setParameters([':member'=>$current->getId()])
                      ->getQuery();
        $page = $request->query->get('page',1);
        $param['entities'] = $this->get('knp_paginator')->paginate($query,$page,50);
        return $param;
    }

    /**
     * @Route("/member/slackparty/myjoin",name="member_slackparty_myjoin")
     * @Method({"GET"})
     * @Template()
     */
    public function myJoinSlackpartyAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_slackparty');
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();
        $repo  = $em->getRepository('SlackissSlackwareBundle:Event');
        $query = $repo->createQueryBuilder('e')
                      ->orderBy('e.id','desc')
                      ->innerJoin('e.players','m')
                      ->where('m = :member')
                      ->setParameters([':member'=>$current->getId()])
                      ->distinct()
                      ->getQuery();
        $page = $request->query->get('page',1);
        $param['entities'] = $this->get('knp_paginator')->paginate($query,$page,50);
        return $param;
    }

    /**
     * @Route("/member/slackparty/eventimage/{id}",name="member_slackparty_eventimage")
     * @Method({"GET"})
     * @Template()
     */
    public function eventImageAction(Request $request,$id)
    {
        $param=array('nav_active'=>'nav_active_slackparty');
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();
        $repo = $em->getRepository('SlackissSlackwareBundle:Event');
        $event = $repo->find($id);
        if(!$event||$current->getId()!==$event->getMember()->getId()){
            throw $this->createNotFoundException('没找到这个活动');
        }
        if(!$event->isExpired()){
            throw $this->createNotFoundException('活动停止报名后才可以上传活动照片');
        }
        $param['event'] = $event;

        $formType = new EventPictureType();
        $eventPicture = new EventPicture();
        $eventPicture->setEvent($event);
        $form = $this->createForm($formType,$eventPicture,[
            'method'=>'POST',
            'action'=>$this->generateUrl('member_slackparty_eventimage_upload',['id'=>$id])
        ]);

        $param['form'] = $form->createView();

        return $param;
    }

    /**
     * @Route("/member/slackparty/eventimageupload/{id}",name="member_slackparty_eventimage_upload")
     * @Method({"POST"})
     * @Template("SlackissSlackwareBundle:SlackParty:eventImage.html.twig")
     */
    public function eventImageUploadAction(Request $request,$id)
    {
        $param=array('nav_active'=>'nav_active_slackparty');
        $em = $this->getDoctrine()->getManager();
        $current = $this->get('security.context')->getToken()->getUser();
        $repo = $em->getRepository('SlackissSlackwareBundle:Event');
        $event = $repo->find($id);
        if(!$event||$current->getId()!==$event->getMember()->getId()){
            throw $this->createNotFoundException('没找到这个活动');
        }
        if(!$event->isExpired()){
            throw $this->createNotFoundException('活动停止报名后才可以上传活动照片');
        }
        $param['event'] = $event;

        $formType = new EventPictureType();
        $eventPicture = new EventPicture();
        $eventPicture->setEvent($event);
        $form = $this->createForm($formType,$eventPicture,[
            'method'=>'POST',
            'action'=>$this->generateUrl('member_slackparty_eventimage_upload',['id'=>$id])
        ]);

        $form->handleRequest($request);
        if($form->isValid()){
            $em->persist($eventPicture);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success','上传成功');
            return $this->redirect($this->generateUrl('member_slackparty_eventimage',['id'=>$id]));
        }

        $param['form'] = $form->createView();

        return $param;
    }

}
