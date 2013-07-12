<?php

namespace Slackiss\Bundle\SlackwareBundle\Service;

class MailService
{
    protected $mailer;
    protected $template;

    public function __construct($mailer,$template)
    {
        $this->mailer = $mailer;
        $this->template = $template;
    }

    public function buildMessage($to, $subject,$contArr,$type)
    {
        $view =     "SlackissSlackwareBundle:email:$type.txt.twig";
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('slackwareim@126.com')
            ->setTo($to)
            ->setBody(
                $this->template->render(
                    $view,
                    $contArr
                )
            )
            ;
        return $message;
    }

    public function send($message)
    {
        $this->mailer->send($message);
    }
}