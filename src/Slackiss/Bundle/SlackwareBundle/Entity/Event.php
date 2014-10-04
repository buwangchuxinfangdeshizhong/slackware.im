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
     * @Assert\NotBlank(message="活动名称不能为空")
     * @Assert\Length(
     *     max="100",
     *     maxMessage="活动名称不能超过100个字"
     * )
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

    public function setImage($image)
    {
        $this->image = $image;
        if($image){
            $this->avatar = $image->getFileName();
        }
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    /**
     * @var string
     * @Assert\NotBlank(message="活动介绍不能为空")
     * @Assert\Length(
     *     max=5000,
     *     maxMessage="活动介绍不能超过5000个字"
     * )
     * @ORM\Column(name="content", type="text",nullable=true)
     */
    private $content;

    /**
     * @Assert\Length(
     *    max=3000,
     *    maxMessage="活动补充不能超过5000个字"
     * )
     * @ORM\Column(name="append",type="text",nullable=true)
     */
    private $append;

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
     * @Assert\Length(
     *     min="1",
     *     max="100",
     *     minMessage="时间介绍不能为空",
     *     maxMessage="时间介绍不能超过100个字"
     * )
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
     * @Assert\Length(
     *     min="1",
     *     max="250",
     *     minMessage="活动地点不能为空",
     *     maxMessage="活动地点不能超过250个字"
     * )
     * @ORM\Column(name="address",type="string",length=250)
     */
    private $address;

    /**
     *
     * @Assert\Length(
     *     min=1,
     *     max=250,
     *     minMessage="活动费用不能为空",
     *     maxMessage="活动费用不能超过250个字"
     * )
     * @ORM\Column(name="fee",type="string",length=250)
     */
    private $fee;

    /**
     *
     * @Assert\Length(
     *     max=200,
     *     maxMessage="现场联系方式不能超过250个字"
     * )
     * @ORM\Column(name="contact",type="string",length=250,nullable=true)
     */
    private $contact;

    /**
     * @Assert\NotBlank(message="请设置最晚报名时间")
     * @ORM\Column(name="last_apply_date",type="datetimetz",nullable=true)
     */
    private $lastApplyDate;

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

    public function hasPlayer(\Slackiss\Bundle\SlackwareBundle\Entity\Member $member)
    {
        $players = $this->players;
        $playerIds = [];
        foreach($players as $player){
            $playerIds[] = $player->getId();
        }
        return in_array($member->getId(),$playerIds);
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

    /**
     * Set contact
     *
     * @param string $contact
     * @return Event
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set lastApplyDate
     *
     * @param \DateTime $lastApplyDate
     * @return Event
     */
    public function setLastApplyDate($lastApplyDate)
    {
        $this->lastApplyDate = $lastApplyDate;

        return $this;
    }

    /**
     * Get lastApplyDate
     *
     * @return \DateTime
     */
    public function getLastApplyDate()
    {
        return $this->lastApplyDate;
    }

    public function isExpired()
    {
        $now = new \DateTime();
        reTurn !$this->lastApplyDate>$now;
    }

    /**
     * Set append
     *
     * @param string $append
     * @return Event
     */
    public function setAppend($append)
    {
        $this->append = $append;

        return $this;
    }

    /**
     * Get append
     *
     * @return string 
     */
    public function getAppend()
    {
        return $this->append;
    }
}
