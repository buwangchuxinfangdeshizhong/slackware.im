<?php

namespace Slackiss\Bundle\SlackwareBundle\Entity;

use DateTime;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * Post
 * @Vich\Uploadable
 * @ORM\Table(name="post")
 * @ORM\Entity(repositoryClass="Slackiss\Bundle\SlackwareBundle\Entity\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Post
{

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->created = new DateTime();
        $this->modified = $this->created;
        $this->lastCommentTime = $this->created;
        $this->commentCount = 0;
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
     *
     * @ORM\Column(name="title", type="string", length=254)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "3",
     *      max = "254",
     *      minMessage = "标题至少需要{{ limit }}个字符 ",
     *      maxMessage = "标题不能多于{{ limit }}个字符"
     * )
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     * @Assert\Length(
     *      min = "0",
     *      minMessage = "不能发布空内容的讨论"
     * )
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
     * @var \DateTime
     *
     * @ORM\Column(name="lastCommentTime", type="datetime")
     */
    private $lastCommentTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="commentCount", type="integer")
     */
    private $commentCount;

    /**
     * @ORM\OneToMany(targetEntity="PostComment", mappedBy="post")
     */
    protected $comments;

    /**
     * @ORM\Column(type="string", length=255, name="attachment",nullable = true)
     *
     * @var string $imageName
     */
	private $attachment;
    
    /**
     * @Assert\File(
     *     maxSize="10M",
     *     mimeTypes={"image/png","image/jpeg","image/pjpeg",
	 *                          "image/jpg","image/gif"}
     * )
     * @Vich\UploadableField(mapping="discuss_image", fileNameProperty="attachment")
     *
     * @var File $image
     */
	private $image;

    public function setImage($image)
    {
        if($image){
            $this->attachment = $image->getFileName();
        }
        $this->image = $image;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Member")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;
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
     * Set title
     *
     * @param string $title
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * Set lastCommentTime
     *
     * @param \DateTime $lastCommentTime
     * @return Post
     */
    public function setLastCommentTime($lastCommentTime)
    {
        $this->lastCommentTime = $lastCommentTime;

        return $this;
    }

    /**
     * Get lastCommentTime
     *
     * @return \DateTime 
     */
    public function getLastCommentTime()
    {
        return $this->lastCommentTime;
    }

    /**
     * Set commentCount
     *
     * @param integer $commentCount
     * @return Post
     */
    public function setCommentCount($commentCount)
    {
        $this->commentCount = $commentCount;

        return $this;
    }

    /**
     * Get commentCount
     *
     * @return integer 
     */
    public function getCommentCount()
    {
        return $this->commentCount;
    }

    /**
     * Add comments
     *
     * @param \Slackiss\Bundle\SlackwareBundle\Entity\PostComment $comments
     * @return Post
     */
    public function addComment(\Slackiss\Bundle\SlackwareBundle\Entity\PostComment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Slackiss\Bundle\SlackwareBundle\Entity\PostComment $comments
     */
    public function removeComment(\Slackiss\Bundle\SlackwareBundle\Entity\PostComment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set member
     *
     * @param \Slackiss\Bundle\SlackwareBundle\Entity\Member $member
     * @return Post
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
     * Set attachment
     *
     * @param string $attachment
     * @return Post
     */
    public function setAttachment($attachment)
    {
        $this->attachment = $attachment;
    
        return $this;
    }

    /**
     * Get attachment
     *
     * @return string 
     */
    public function getAttachment()
    {
        return $this->attachment;
    }
}