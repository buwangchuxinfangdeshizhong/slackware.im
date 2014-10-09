<?php

namespace Slackiss\Bundle\SlackwareBundle\Service;

use Symfony\Component\HttpKernel\Kernel;
use Slackiss\Bundle\SlackwareBundle\Entity\Message;

class MessageService {

    protected $em;
    protected $paginator;

    public function __construct($em,$paginator)
    {
        $this->em        = $em;
        $this->paginator = $paginator;
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
        $messages = $query->getResults();
        foreach($messages as $message){
            //send email
            //message->setMail(true);
            //$this->em->persist($message);
            //$this->em->flush();
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
