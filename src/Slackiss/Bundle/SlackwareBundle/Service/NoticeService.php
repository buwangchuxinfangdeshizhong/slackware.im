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
