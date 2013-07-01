<?php

namespace Slackiss\Bundle\SlackwareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/slacker")
 */
class SlackerController extends Controller
{
    /**
     * @Route("/{memberId}",name="slacker")
     * @Template()
     * @Method({"GET"})
     */
    public function indexAction(Request $request,$memberId=0)
    {
        $param=array('nav_active'=>'nav_active_install');
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

}

