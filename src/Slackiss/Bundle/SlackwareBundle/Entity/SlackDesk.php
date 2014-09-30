<?php

namespace Slackiss\Bundle\SlackwareBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * SlackDesk
 *
 * @Vich\Uploadable
 * @ORM\Table(name="slackdesk")
 * @ORM\Entity(repositoryClass="Slackiss\Bundle\SlackwareBundle\Entity\SlackDeskRepository")
 */
class SlackDesk
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
     * @Assert\File(
     *     maxSize="10M",
     *     mimeTypes={"image/png","image/jpeg","image/pjpeg",
	 *                          "image/jpg","image/gif"}
     * )
     * @Vich\UploadableField(mapping="desk_image", fileNameProperty="desk")
     *
     * @var File $image
     */
    private $image;


    /**
     * @ORM\ManyToOne(targetEntity="Member")
     * @ORM\JoinColumn(name="member_id",referencedColumnName="id")
     */
    private $member ;

    /**
     * @var string
     *
     * @ORM\Column(name="desk", type="string", length=255)
     */
    private $desk;

    /**
     * @var string
     * @Assert\Length(
     *     max="500",
     *     maxMessage="故事内容不能超过500字"
     * )
     * @ORM\Column(name="description", type="text",nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetimetz")
     */
    private $created;


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
     * Set image
     *
     * @param string $image
     * @return SlackDesk
     */
    public function setImage($image)
    {
        $this->image = $image;
        if($image){
            $this->desk = $image->getFileName();
        }
        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set desk
     *
     * @param string $desk
     * @return SlackDesk
     */
    public function setDesk($desk)
    {
        $this->desk = $desk;
    
        return $this;
    }

    /**
     * Get desk
     *
     * @return string 
     */
    public function getDesk()
    {
        return $this->desk;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return SlackDesk
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return SlackDesk
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
     * @return SlackDesk
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
}