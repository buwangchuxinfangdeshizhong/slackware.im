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

    public function __construct($mail,$em,$security)
    {
        $this->mail = $mail;
        $this->em   = $em;
        $this->security = $security;
    }

}