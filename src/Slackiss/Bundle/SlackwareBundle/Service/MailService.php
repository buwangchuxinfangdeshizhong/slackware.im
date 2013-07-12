<?php

namespace Slackiss\Bundle\SlackwareBundle\Service;

class MailService
{
    protected $mailer;

    public function __construct($mailer)
    {
        $this->mailer = mailer;
    }

    public function buildMessage($to, $subject,$content,$type)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('slackwareim@126.com')
            ->setTo($to)
            ->setBody(
                $this->renderView(
                    "SlackissSlackwareBundle:email:$type.txt.twig",
                    array('content' => $content)
                )
            )
            ;
    }

    public function send($addr, $message)
    {
        $this->mailer->send($message);
    }
}