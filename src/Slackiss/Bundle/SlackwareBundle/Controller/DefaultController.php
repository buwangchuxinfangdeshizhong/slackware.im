<?php

namespace Slackiss\Bundle\SlackwareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/",name="welcome")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $securityContext = $this->container->get('security.context');
        if ($securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $current = $this->get('security.context')->getToken()->getUser();
            if($current->getWeiboAccessToken()&&$current->getEmail()==$current->getUsername()){
                $buzz = $this->container->get('buzz');
                $response = $buzz->get('https://api.weibo.com/2/account/profile/email.json?access_token='.$current->getWeiboAccessToken());
                $response = json_decode($response);
                $email = $response['email'];
                $current->setEmail($email);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
            }
        }
        $param=array('nav_active'=>'nav_active_news');
        $em = $this->getDoctrine()->getManager();
        $page = $request->query->get('page',1);
        $query = $em->getRepository('SlackissSlackwareBundle:News')
                    ->createQueryBuilder('n')
                    ->where('n.type = :type')
                    ->setParameters(array(':type'=>0))
                    ->orderBy('n.id','desc')
                    ->getQuery();
        $newses = $this->get('knp_paginator')->paginate($query,$page,4);
        $param['newses'] = $newses;
        return $param;
    }
}
