<?php

namespace Slackiss\Bundle\SlackwareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class NewsController extends Controller
{
    /**
     * @Route("/manager/news/new",name="news_new")
     * @Template()
     * @Method({"GET"})
     */
    public function newAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_news');
        return $param;
    }
}

