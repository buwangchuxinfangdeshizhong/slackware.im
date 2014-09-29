<?php

namespace Slackiss\Bundle\SlackwareBundle\Service;

use Slackiss\Bundle\SlackwareBundle\Entity\Post;
use Slackiss\Bundle\SlackwareBundle\Entity\PostComment;
use Slackiss\Bundle\SlackwareBundle\Entity\Member;

class PostService
{
    protected $mail;
    protected $em;
    protected $security;
    protected $route;
    protected $req;

    public function __construct($em,$mail,$security,$route,$req)
    {
        $this->mail = $mail;
        $this->em   = $em;
        $this->security = $security;
        $this->route = $route;
        $this->req = $req;
    }

    public function getLastComment($post)
    {
        $commentRepo = $this->em->getRepository('SlackissSlackwareBundle:PostComment');
        $query = $commentRepo->createQueryBuilder('c')
                             ->where('c.post = :post')
                             ->setParameters(array(':post'=>$post->getId()))
                             ->orderBy('c.id','desc')
                             ->setMaxResults(1)
                             ->setFirstResult(0)
                             ->getQuery();
        try {
            $comment = $query->getSingleResult();
        } catch (\Doctrine\Orm\NoResultException $e) {
            $comment = null;
        }
        return $comment;
    }

    public function getCategories()
    {
        $repo = $this->em->getRepository('SlackissSlackwareBundle:PostCategory');
        $query = $repo->createQueryBuilder('c')
                      ->orderBy('c.sequence','asc')
                      ->where('c.status = true and c.enabled = true')
                      ->getQuery();
        return $query->getResult();
    }

    /**
     * 刚看到自己几年前这段代码,真烂
     */
    public function notify($post)
    {
        $lastComment = $this->getLastComment($post);
        $currentEmail = "";
        if($lastComment){
            $currentEmail = $lastComment->getMember()->getEmail();
        }
        $emails = array();
        if($currentEmail!=$post->getMember()->getEmail()){
            $emails[] = $post->getMember()->getEmail();
        }
        $comments = $post->getComments();
        foreach($comments as $c){
            $email = $c->getMember()->getEmail();
            if(!in_array($email,$emails)&&$email!=$currentEmail){
                $emails[] = $email;
            }
        }

        $subject = '[slackware.im] 您参与的帖子\"'.$post->getTitle().'"有了新回复';
        $content = array();
        $content['post']=$post;
        /**
           app.request.scheme :// app.request.httpHost app.request.basePath
        */
        $content['url'] =
            $this->req->getScheme()."://".
            $this->req->getHttpHost().
            $this->req->getBasePath().
            $this->route->generate('post_show',array('id'=>$post->getId()));
        foreach($emails as $to){
            $message = $this->mail->buildMessage($to,$subject,$content,'postcomment');
            $this->mail->send($message);
        }
    }

}