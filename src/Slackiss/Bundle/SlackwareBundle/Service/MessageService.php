<?php

namespace Slackiss\Bundle\SlackwareBundle\Service;

use Symfony\Component\HttpKernel\Kernel;
use Slackiss\Bundle\SlackwareBundle\Entity\Message;

class MessageService {

    protected $em;
    protected $paginator;
    protected $mail;
    protected $route;
    protected $noticeService;

    public function __construct($em,$paginator,$mail, $route,$noticeService)
    {
        $this->em        = $em;
        $this->paginator = $paginator;
        $this->mail      = $mail;
        $this->route     = $route;
        $this->noticeService = $noticeService;
    }

    public function getMessages($member,$page=1,$limit=50)
    {
        $repo = $this->em->getRepository('SlackissSlackwareBundle:Message');
        $query = $repo->createQueryBuilder('m')
                      ->where('m.member = :member')
                      ->setParameters([':member'=>$member->getId()])
                      ->orderBy('m.id','desc')
                      ->getQuery();
        return $this->paginator->paginate($query,$page,$limit);
    }

    public function getNewMessageCount($member)
    {
        $repo = $this->em->getRepository('SlackissSlackwareBundle:Message');
        $query = $repo->createQueryBuilder('m')
                      ->select('count(m)')
                      ->where('m.member = :member and m.read=false')
                      ->setParameters([':member'=>$member->getId()])
                      ->orderBy('m.id','desc')
                      ->getQuery();
        return $query->getSingleScalarResult();
    }

    public function readMessage($id,$member)
    {
        $repo = $this->em->getRepository('SlackissSlackwareBundle:Message');
        $message = $repo->findOneBy(['member'=>$member->getId(),'id'=>$id]);
        if($message){
            $message->setRead(true);
            $this->em->persist($message);
            $this->em->flush();
        }
        return $message;
    }

    public function mailMessages()
    {
        $repo = $this->em->getRepository('SlackissSlackwareBundle:Message');
        $query = $repo->createQueryBuilder('m')
                      ->where('m.mail = false')
                      ->getQuery();
        $messages = $query->getResult();
        foreach($messages as $message){
            $subject = '[slackware.im] 您收到了新的消息，请注意查收';
            $content = [];
            $content['message']=$message;
            if($message->getAction()){
                $content['url'] ='http://slackware.im/'.
                    $this->route->generate($message->getAction(),$message->getParams());
            }

            $member = $message->getMember();
            $noticeSetting = $this->noticeService->getSetting($member);
            if($noticeSetting->getSendEmail()){
                foreach($noticeSetting->getEmails() as $email){
                    $msg = $this->mail->buildMessage($email,$subject,$content,'postcomment');
                    $this->mail->send($msg);

                }
            }
            $message->setMail(true);
            $this->em->persist($message);
            $this->em->flush();
        }
    }

    public function pushMessage($member, $content, $action=null, $params=[])
    {
        $repo = $this->em->getRepository('SlackissSlackwareBundle:Message');
        $message = new Message();
        $message->setMember($member);
        $message->setContent($content);
        $message->setAction($action);
        $message->setParams($params);
        $this->em->persist($message);
        $this->em->flush();
        return $message;
    }
}
