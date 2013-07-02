<?php

namespace Slackiss\Bundle\SlackwareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Slackiss\Bundle\SlackwareBundle\Entity\News;
use Slackiss\Bundle\SlackwareBundle\Entity\Member;
use Slackiss\Bundle\SlackwareBundle\Form\NewsType;

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
        $news = new News();
        $newsType = new NewsType();
        $form = $this->createForm($newsType,$news,array(
            'action'=>$this->generateUrl('news_create'),
            'method'=>'POST'
        ));
        $param['form'] = $form->createView();
        $param['news'] = $news;
        return $param;
    }
    
    /**
     * @Method({"POST"})
     * @Template()
     * @Route("/manager/news/create",name="news_create")
     */
    public function createAction(Request $request)
    {
        $param = array('nav_active'=>'nav_active_news');
        $news = new News();
        $newsType = new NewsType();
        $form = $this->createForm($newsType,$news,array(
            'action'=>$this->generateUrl('news_create'),
            'method'=>'POST'
        ));
        
        if($form->isValid()){
            $current = $this->get('security.context')->getToken()->getUser();
            $news->setMember($current);
            $news->setType(News::TYPE_ANNOUNCEMENT);
            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success','资讯添加成功');
            return $this->redirect($this->generateUrl('welcome'));
                
        }

        $param['form'] = $form->createView();
        $param['news'] = $news;
        return $param;
    }

}

