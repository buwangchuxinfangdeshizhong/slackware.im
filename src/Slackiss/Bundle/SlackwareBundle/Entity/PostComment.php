<?php

namespace Slackiss\Bundle\SlackwareBundle\Entity;

use DateTime;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PostComment
 *
 * @ORM\Table(name="post_comment")
 * @ORM\Entity(repositoryClass="Slackiss\Bundle\SlackwareBundle\Entity\PostCommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PostComment
{
    public function __construct()
    {
        $this->created = new DateTime();
        $this->modified = $this->created;
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
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3",
     *      minMessage = "评论内容至少需要{{ limit }}个字"
     * )
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified", type="datetime")
     */
    private $modified;

    /**
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="Member")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;
    
    /**
     * @ORM\PostPersist
     */
    public function increasePostCommentCount()
    {
        $this->post->setCommentCount($this->post->getCommentCount()+1);
    }

    /**
     * @ORM\PostRemove
     */
    public function decreasePostCommentCount()
    {
        $count = $this->post->getCommentCount()-1;
        $this->post->setCommentCount($count>=0?$count:0);
    }

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
     * @return PostComment
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
     * @return PostComment
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
     * Set modified
     *
     * @param \DateTime $modified
     * @return PostComment
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime 
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set post
     *
     * @param \Slackiss\Bundle\KneworkBundle\Entity\Post $post
     * @return PostComment
     */
    public function setPost(\Slackiss\Bundle\KneworkBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \Slackiss\Bundle\KneworkBundle\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set member
     *
     * @param \Slackiss\Bundle\KneworkBundle\Entity\Member $member
     * @return PostComment
     */
    public function setMember(\Slackiss\Bundle\KneworkBundle\Entity\Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \Slackiss\Bundle\KneworkBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }
}
