<?php

namespace Slackiss\Bundle\SlackwareBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PostEmailNotice
 *
 * @ORM\Table(name="post_email_notice")
 * @ORM\Entity(repositoryClass="Slackiss\Bundle\SlackwareBundle\Entity\PostEmailNoticeRepository")
 */
class PostEmailNotice
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Member")
     * @ORM\JoinColumn(name="member_id",referencedColumnName="id")
     */
    private $member;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="Post")
     * @ORM\JoinColumn(name="post_id",referencedColumnName"id")
     */
    private $post;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set member
     *
     * @param string $member
     * @return PostEmailNotice
     */
    public function setMember($member)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return string
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set post
     *
     * @param string $post
     * @return PostEmailNotice
     */
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return string
     */
    public function getPost()
    {
        return $this->post;
    }
}
