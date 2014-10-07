<?php

namespace Slackiss\Bundle\SlackwareBundle\Entity;

use DateTime;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ChangeLog
 *
 * @ORM\Table(name="change_log")
 * @ORM\Entity(repositoryClass="Slackiss\Bundle\SlackwareBundle\Entity\ChangeLogRepository")
 */
class ChangeLog
{

    const TYPE_X86=1;
    const TYPE_X64=2;
    const TYPE_ARM=3;
    const TYPE_390=4;
    const TYPE_SX86=5;
    const TYPE_SX64=6;
    const TYPE_S390=7;
    const TYPE_SARM=8;
    const TYPE_S39064=9;
    const TYPE_36064=10;
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
     * @Assert\NotBlank(message="内容不能为空")
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     *
     * @Assert\Range(
     *      min = 1,
     *      max = 10,
     *      minMessage = "请设置合法的状态",
     *      maxMessage = "请设置合法的状态"
     * )
     * @ORM\Column(name="type",type="string",length=255)
     */
    private $type;

    private $typeName;

    public function getTypeName()
    {
        if($this->type==1){
            return 'Slackware-current X86 ChangeLog';
        }
        if($this->type==2){
            return 'Slackware-current X86_64 ChangeLog';
        }
        if($this->type==3){
            return 'Slackware-current ARM ChangeLog';
        }
        if($this->type==4){
            return 'Slackware-current S/390(31-bit) ChangeLog';
        }
        if($this->type==5){
            return 'Slackware-stable X86 ChangeLog';
        }
        if($this->type==6){
            return 'Slackware-stable X86_64 ChangeLog';
        }
        if($this->type==7){
            return 'Slackware-stable S/390(31-bit) ChangeLog';
        }
        if($this->type==8){
            return 'Slackware-stable ARM ChangeLog';
        }
        if($this->type==9){
            return 'Slackware-stable S/390(64-bit) ChangeLog';
        }
        if($this->type==10){
            return 'Slackware-current S/390(64-bit) ChangeLog';
        }
    }

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
     * Set content
     *
     * @param string $content
     * @return ChangeLog
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
     * @return ChangeLog
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
     * Set type
     *
     * @param string $type
     * @return ChangeLog
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
