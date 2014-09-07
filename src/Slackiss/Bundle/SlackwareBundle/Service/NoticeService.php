<?php

namespace Slackiss\Bundle\SlackwareBundle\Service;

use Symfony\Component\HttpKernel\Kernel;
use Slackiss\Bundle\SlackwareBundle\Entity\NoticeEmailSetting;

class NoticeService {

    protected $em;
    protected $paginator;

    public function __construct($em,$paginator)
    {
        $this->em        = $em;
        $this->paginator = $paginator;
    }

    public function getSetting($member)
    {
        $repo = $this->em->getRepository('SlackissSlackwareBundle:NoticeEmailSetting');
        $setting = $repo->findOneBy(['member'=>$member->getId()]);
        if(!$setting){
            $setting = $this->initSetting($member);
        }
        return $setting;
    }

    public function setPostNotice($member,$post)
    {
        $repo = $this->em->getRepository('SlackissSlackwareBundle:PostEmailNotice');
        $entity = $repo->findOneBy([
            'member'=>$member->getId(),
            'post'=>$post->getId()
        ]);
        if($entity){
            $this->em->remove($entity);
        }else{
            $entity = new PostEmailNotice();
            $entity->setMember($member);
            $entity->setPost($post);
            $this->em->persist($entity);
        }
        $this->em->flush();
    }

    public function removePostNotices($post)
    {
        $repo = $this->em->getRepository('SlackissSlackwareBundle:PostEmailNotice');
        $entities = $repo->findBy(['post'=>$post->getId()]);
        foreach($entities as $e){
            $this->em->remove($e);
        }
        $this->em->flush();
    }

    private function initSetting($member)
    {
        $setting = new NoticeEmailSetting();
        $setting->setMember($member);
        $setting->setEmail1($member->getEmail());
        $setting->setSendEmail(true);
        $this->em->persist($setting);
        $this->em->flush();
        return $setting;
    }

}
