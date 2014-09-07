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
        $posts = $this->get('knp_paginator')->paginate($query,$page,50);
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
     * @Template("SlackissSlackwareBundle:post:new.html.twig")
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
            $this->get('session')->getFlashBag()->add('success','成功创建讨论:'.$post->getTitle());
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

        $page = $request->query->get('page',1);
        $comments = $this->getComments($post,$page);
        $param['comments'] = $comments;

        $comment = new PostComment();
        $form = $this->getCommentForm($post,$comment);

        $param['form'] = $form->createView();
        return $param;
    }

    /**
     * @Route("/member/post/comment/update/{id}",name="post_comment_create")
     * @Template("SlackissSlackwareBundle:Post:show.html.twig")
     * @Method({"POST"})
     */
    public function commentCreateAction(Request $request,$id)
    {
        $param=array('nav_active'=>'nav_active_post');
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('SlackissSlackwareBundle:Post')->find($id);
        if(!$post){
            throw $this->createNotFoundException("这个帖子不存在");
        }
        $param['post'] = $post;

        $comment = new PostComment();
        $form = $this->getCommentForm($post,$comment);
        $form->handleRequest($request);
        if($form->isValid()){
            $current = $this->get('security.context')->getToken()->getUser();
            $comment->setPost($post);
            $comment->setMember($current);
            $em->persist($comment);
            $em->flush();
            $post = $comment->getPost();
            $post->setLastCommentTime(new \DateTime());
            $em->persist($post);
            $em->flush();
            $this->get('slackiss_slackware.post')->notify($post);
            $this->get('session')->getFlashBag()->add('success','回复成功');
            return $this->redirect($this->generateUrl('post_show',array('id'=>$post->getId())));
        }

        $param['form'] = $form->createView();

        $page = $request->query->get('page',1);
        $comments = $this->getComments($post,$page);
        $param['comments'] = $comments;

        return $param;
    }

    /**
     *
     * @Route("/manager/post/delete/{id}",name="post_delete")
     * @Method({"GET","DELETE"})
     *
     */
    public function deletePostAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('SlackissSlackwareBundle:Post')->find($id);
        if($post){
            $this->get('slackiss_slackware.notice')->removePostNotices($post);
            $em->remove($post);
            $em->flush();
        }
        $this->get('session')->getFlashBag()->add('success','删除成功');
        return $this->redirect($this->generateUrl('post'));
    }

    /**
     *
     * @Route("/manager/postcomment/delete/{id}",name="postcomment_delete")
     * @Method({"GET","DELETE"})
     *
     */
    public function deletePostCommentAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $postComment = $em->getRepository('SlackissSlackwareBundle:PostComment')->find($id);
        if($postComment){
            $em->remove($postComment);
            $em->flush();
            $em->persist($postComment->getPost());
            $em->flush();
        }
        $this->get('session')->getFlashBag()->add('success','删除成功');
        return $this->redirect($this->generateUrl('post'));
    }

    private function getCommentForm($post,$comment)
    {
        $commentType = new PostCommentType();
        $form = $this->createForm($commentType,$comment,array(
            'action'=>$this->generateUrl('post_comment_create',array('id'=>$post->getId())),
            'method'=>'POST'
        ));
        return $form;
    }

    private function getComments($post,$page)
    {
        $query = $this->getDoctrine()->getManager()
                      ->getRepository('SlackissSlackwareBundle:PostComment')
                      ->createQueryBuilder('c')
                      ->where('c.post = :post')
                      ->setParameters(array(':post'=>$post->getId()))
                      ->orderBy('c.id','asc')
                      ->getQuery();
        $comments = $this->get('knp_paginator')->paginate($query,$page,50);
        return $comments;
    }
}
