<?php

namespace Slackiss\Bundle\SlackwareBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 *
 * @ORM\Table(name="event")
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass="Slackiss\Bundle\SlackwareBundle\Entity\EventRepository")
 */
class Event
{

    public function __construct()
    {
        $this->players = new ArrayCollection();
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
     *
     * @ORM\Column(name="title", type="string", length=2000)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=2000,nullable=true)
     */
    private $avatar;

    /**
	 * @Assert\File(
	 *     maxSize="10M",
	 *     mimeTypes={"image/png","image/jpeg","image/pjpeg",
	 *                          "image/jpg","image/gif"}
	 * )
	 * @Vich\UploadableField(mapping="party_image", fileNameProperty="avatar")
	 *
	 * @var File $image
	 */
    private $image;

    public function setImage(UploadedFile $image)
    {
        $this->image = $image;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text",nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="Member")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;

    /**
     * @ORM\ManyToMany(targetEntity="Member")
     * @ORM\JoinTable(name="member_event",
     * joinColumns={@ORM\JoinColumn(name="event_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="member_id", referencedColumnName="id")}
     * )
     */
    protected $players;

    /**
     * @ORM\Column(name="eventdate", type="string",length=100,nullable=false)
     */
    protected $eventdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

   /**
    * @var string
    *
    * @ORM\Column(name="address",type="string",length=250)
	*/
	private $address;

    /**
	 * @ORM\Column(name="fee",type="string",length=250)
	 */
	private $fee;
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
     * @return Event
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
     * Set avatar
     *
     * @param string $avatar
     * @return Event
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Event
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
     * Set eventdate
     *
     * @param string $eventdate
     * @return Event
     */
    public function setEventdate($eventdate)
    {
        $this->eventdate = $eventdate;
    
        return $this;
    }

    /**
     * Get eventdate
     *
     * @return string 
     */
    public function getEventdate()
    {
        return $this->eventdate;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Event
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
     * Set address
     *
     * @param string $address
     * @return Event
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set fee
     *
     * @param string $fee
     * @return Event
     */
    public function setFee($fee)
    {
        $this->fee = $fee;
    
        return $this;
    }

    /**
     * Get fee
     *
     * @return string 
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * Set member
     *
     * @param \Slackiss\Bundle\SlackwareBundle\Entity\Member $member
     * @return Event
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
     * Add players
     *
     * @param \Slackiss\Bundle\SlackwareBundle\Entity\Member $players
     * @return Event
     */
    public function addPlayer(\Slackiss\Bundle\SlackwareBundle\Entity\Member $players)
    {
        $this->players[] = $players;
    
        return $this;
    }

    /**
     * Remove players
     *
     * @param \Slackiss\Bundle\SlackwareBundle\Entity\Member $players
     */
    public function removePlayer(\Slackiss\Bundle\SlackwareBundle\Entity\Member $players)
    {
        $this->players->removeElement($players);
    }

    /**
     * Get players
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlayers()
    {
        return $this->players;
    }
}