<?php

namespace Slackiss\Bundle\SlackwareBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Slackiss\Bundle\SlackwareBundle\Entity\ChangeLog;
use Slackiss\Bundle\SlackwareBundle\Form\ChangeLogType;

class ChangeLogController extends Controller
{
    /**
     * @Route("/changelog/",name="changelog")
     * @Template()
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_changelog');
        return $param;
    }

    /**
     * @Route("/changelog/details",name="changelog_details")
     * @Method({"GET"})
     * @Template()
     */
    public function detailsAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_changelog');
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('SlackissSlackwareBundle:ChangeLog');
        $page = $request->query->get('page',1);
        $query = $repo->createQueryBuilder('c')
                      ->orderBy('c.id','desc')
                      ->getQuery();
        $changeLogs = $this->get('knp_paginator')->paginate($query,$page,10);
        $param['changeLogs']=$changeLogs;
        return $param;
    }

    /**
     * @Route("/changelog/show/{id}",name="changelog_show")
     * @Method({"GET"})
     * @Template()
     */
    public function showAction(Request $request,$id)
    {
        $repo = $this->getDoctrine()->getManager()
                     ->getRepository('SlackissSlackwareBundle:ChangeLog');
        $changeLog = $repo->find($id);
        if(!$changeLog){
            throw $this->createNotFoundException('没有这个变更日志');
        }
        $param=array('nav_active'=>'nav_active_changelog');
        $param['changeLog'] = $changeLog;
        return $param;
    }

    /**
     * @Route("/admin/changelog/new",name="admin_changelog_new")
     * @Method({"GET"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_changelog');
        $changeLog = new ChangeLog();
        $changeLogType = new ChangeLogType();
        $form = $this->createForm($changeLogType,$changeLog,array(
            'action'=>$this->generateUrl('admin_changelog_create'),
            'method'=>'POST'
        ));

        $param['form'] = $form->createView();
        $param['changelog'] = $changeLog;
        return $param;
    }

    /**
     * @Route("/admin/changelog/create",name="admin_changelog_create")
     * @Method({"POST"})
     * @Template()
     */
    public function createAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_changelog');
        $changeLog = new ChangeLog();
        $changeLogType = new ChangeLogType();
        $form = $this->createForm($changeLogType,$changeLog,array(
            'action'=>$this->generateUrl('admin_changelog_create'),
            'method'=>'POST'
        ));
        $form->handleRequest($request);
        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($changeLog);
            $em->flush();
            return $this->redirect($this->generateUrl('changelog_details'));
        }
        $param['form'] = $form->createView();
        $param['changelog'] = $changeLog;
        return $param;
    }
}

