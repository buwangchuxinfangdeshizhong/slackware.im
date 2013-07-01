<?php

namespace Slackiss\Bundle\SlackwareBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Slackiss\Bundle\SlackwareBundle\Entity\Post;
use Slackiss\Bundle\SlackwareBundle\Entity\PostComment;

use Slackiss\Bundle\SlackwareBundle\Form\PostType;
use Slackiss\Bundle\SlackwareBundle\Form\PostCommentType;


class PostController extends Controller
{
    /**
     * @Route("/post/",name="post")
     * @Template()
     * @Method({"GET"})
     */
    public function indexAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_post');
        $page = $request->query->get('page',1);
        $query = $this->getDoctrine()->getManager()
                      ->getRepository('SlackissSlackwareBundle:Post')
                      ->createQueryBuilder('p')
                      ->orderBy('p.lastCommentTime','desc')
                      ->getQuery();
        $posts = $this->get('knp_paginator')->paginate($query,$page,100);
        $param['posts']=$posts;
        return $param;
    }

    /**
     * @Route("/post/new",name="post_new")
     * @Template()
     * @Method({"GET"})
     */
    public function newAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_post');        
        $post = new Post();
        $postType = new PostType();
        $form = $this->createForm($postType,$post,array(
            'action'=>$this->generateUrl('post_create'),
            'method'=>'POST'
        ));
        $param['form']=$form->createView();
        return $param;
    }

    /**
     * @Route("/member/post/create",name="post_create")
     * @Template()
     * @Method({"POST"})
     */
    public function createAction(Request $request)
    {
        $param=array('nav_active'=>'nav_active_post');        
        $post = new Post();
        $postType = new PostType();
        $form = $this->createForm($postType,$post,array(
            'action'=>$this->generateUrl('post_create'),
            'method'=>'POST'
        ));
        
        $form->handleRequest($request);
        if($form->isValid()){
            $current = $this->get('security.context')->getToken()->getUser();
            $post->setMember($current);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success','成功创建讨论'.$post->getTitle());
            return $this->redirect($this->generateUrl('post_show',array('id'=>$post->getId())));
        }
        $param['form']=$form->createView();
        return $param;
    }

    /**
     * @Route("/post/{id}",name="post_show")
     * @Method({"GET"})
     * @Template()
     */
    public function showAction(Request $request, $id)
    {
        $param=array('nav_active'=>'nav_active_post');   
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('SlackissSlackwareBundle:Post')->find($id);
        if(!$post){
            throw $this->createNotFoundException("这个帖子不存在");
        }
        $param['post'] = $post;

        return $param;
    }
}

