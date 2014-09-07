<?php

namespace Slackiss\Bundle\SlackwareBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * NoticeEmailSetting
 *
 * @ORM\Table(name="notice_email_setting")
 * @ORM\Entity(repositoryClass="Slackiss\Bundle\SlackwareBundle\Entity\NoticeEmailSettingRepository")
 */
class NoticeEmailSetting
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
     * @ORM\ManyToOne(targetEntity="Member")
     * @ORM\JoinColumn(name="member_id",referencedColumnName="id",unique=true)
     */
    private $member;

    /**
     * @var string
     *
     * @Assert\Email(
     *    checkMX=true,
     *    message="请使用合法的电子信箱"
     * )
     * @Assert\Length(
     *    max=255,
     *    maxMessage="电子信箱长度不能超过255个字符"
     * )
     * @ORM\Column(name="email1", type="string", length=255,nullable=true)
     */
    private $email1;

    /**
     * @var string
     * @Assert\Email(
     *    checkMX=true,
     *    message="请使用合法的电子信箱"
     * )
     * @Assert\Length(
     *    max=255,
     *    maxMessage="电子信箱长度不能超过255个字符"
     * )
     *
     * @ORM\Column(name="email2", type="string", length=255,nullable=true)
     */
    private $email2;

    /**
     * @var string
     * @Assert\Email(
     *    checkMX=true,
     *    message="请使用合法的电子信箱"
     * )
     * @Assert\Length(
     *    max=255,
     *    maxMessage="电子信箱长度不能超过255个字符"
     * )
     *
     * @ORM\Column(name="email3", type="string", length=255,nullable=true)
     */
    private $email3;

    /**
     * @var string
     * @Assert\Email(
     *    checkMX=true,
     *    message="请使用合法的电子信箱"
     * )
     * @Assert\Length(
     *    max=255,
     *    maxMessage="电子信箱长度不能超过255个字符"
     * )
     *
     * @ORM\Column(name="email4", type="string", length=255,nullable=true)
     */
    private $email4;

    /**
     * @var string
     *
     * @ORM\Column(name="send_email", type="boolean", nullable=true)
     */
    private $sendEmail;


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
     * @return NoticeEmailSetting
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
     * Set email1
     *
     * @param string $email1
     * @return NoticeEmailSetting
     */
    public function setEmail1($email1)
    {
        $this->email1 = $email1;

        return $this;
    }

    /**
     * Get email1
     *
     * @return string
     */
    public function getEmail1()
    {
        return $this->email1;
    }

    /**
     * Set email2
     *
     * @param string $email2
     * @return NoticeEmailSetting
     */
    public function setEmail2($email2)
    {
        $this->email2 = $email2;

        return $this;
    }

    /**
     * Get email2
     *
     * @return string
     */
    public function getEmail2()
    {
        return $this->email2;
    }

    /**
     * Set email3
     *
     * @param string $email3
     * @return NoticeEmailSetting
     */
    public function setEmail3($email3)
    {
        $this->email3 = $email3;

        return $this;
    }

    /**
     * Get email3
     *
     * @return string
     */
    public function getEmail3()
    {
        return $this->email3;
    }

    /**
     * Set email4
     *
     * @param string $email4
     * @return NoticeEmailSetting
     */
    public function setEmail4($email4)
    {
        $this->email4 = $email4;

        return $this;
    }

    /**
     * Get email4
     *
     * @return string
     */
    public function getEmail4()
    {
        return $this->email4;
    }


    public function getEmails()
    {
        $emails = [];
        if($this->email1){
            $emails[] = $this->email1;
        }
        if($this->email2){
            $emails[] = $this->email2;
        }
        if($this->email3){
            $emails[] = $this->email3;
        }
        if($this->email4){
            $emails[] = $this->email4;
        }
        return $emails;
    }

    /**
     * Set sendEmail
     *
     * @param boolean $sendEmail
     * @return NoticeEmailSetting
     */
    public function setSendEmail($sendEmail)
    {
        $this->sendEmail = $sendEmail;

        return $this;
    }

    /**
     * Get sendEmail
     *
     * @return boolean 
     */
    public function getSendEmail()
    {
        return $this->sendEmail;
    }
}
