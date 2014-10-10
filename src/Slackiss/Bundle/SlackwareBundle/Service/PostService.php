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
    protected $messageService;

    public function __construct($em,$mail,$security,$route,$req,$messageService)
    {
        $this->mail = $mail;
        $this->em   = $em;
        $this->security = $security;
        $this->route = $route;
        $this->req = $req;
        $this->messageService = $messageService;
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

    public function notify($post)
    {
        $postEmailNotices = $this->em->getRepository('SlackissSlackwareBundle:PostEmailNotice')
                            ->findBy(['post'=>$post->getId()]);

        $url =
            $this->req->getScheme()."://".
            $this->req->getHttpHost().
            $this->req->getBasePath().
            $this->route->generate('post_show',array('id'=>$post->getId()));
        $content = '您关注的帖子\"'.$post->getTitle().'"有了新回复';
        $content = $content.' 您可以点击下面的链接查看： ';
        $content = $content.$url;

        /**
           app.request.scheme :// app.request.httpHost app.request.basePath
        */
        foreach($postEmailNotices as $notice){
            $member = $notice->getMember();
            $this->messageService->pushMessage($member, $content,'post_show',['id'=>$post->getId()]);
        }
    }

}