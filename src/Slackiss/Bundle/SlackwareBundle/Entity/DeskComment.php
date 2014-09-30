<?php

namespace Slackiss\Bundle\SlackwareBundle\Entity;

use DateTime;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DeskComment
 *
 * @ORM\Table(name="desk_comment")
 * @ORM\Entity(repositoryClass="Slackiss\Bundle\SlackwareBundle\Entity\DeskCommentRepository")
 */
class DeskComment
{

    public function __construct()
    {
        $this->created = new DateTime();
    }

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
     * @Assert\NotBlank(message="评论内容不能为空")
     * @Assert\Length(
     *     max="5000",
     *     maxMessage = "评论内容不能超过5000字"
     * )
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetimetz")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="Member")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;

    /**
     *
     * @ORM\ManyToOne(targetEntity="SlackDesk")
     * @ORM\JoinColumn(name="slackdesk_id",referencedColumnName="id")
     */
    private $slackDesk;

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
     * Set content
     *
     * @param string $content
     * @return DeskComment
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return DeskComment
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set member
     *
     * @param \Slackiss\Bundle\SlackwareBundle\Entity\Member $member
     * @return DeskComment
     */
    public function setMember(\Slackiss\Bundle\SlackwareBundle\Entity\Member $member = null)
    {
        $this->member = $member;
    
        return $this;
    }

    /**
     * Get member
     *
     * @return \Slackiss\Bundle\SlackwareBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set slackDesk
     *
     * @param \Slackiss\Bundle\SlackwareBundle\Entity\SlackDesk $slackDesk
     * @return DeskComment
     */
    public function setSlackDesk(\Slackiss\Bundle\SlackwareBundle\Entity\SlackDesk $slackDesk = null)
    {
        $this->slackDesk = $slackDesk;
    
        return $this;
    }

    /**
     * Get slackDesk
     *
     * @return \Slackiss\Bundle\SlackwareBundle\Entity\SlackDesk 
     */
    public function getSlackDesk()
    {
        return $this->slackDesk;
    }
}