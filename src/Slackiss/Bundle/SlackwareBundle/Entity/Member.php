<?php
namespace Slackiss\Bundle\SlackwareBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 * @ORM\Table(name="member")
 * @ORM\Entity(repositoryClass="Slackiss\Bundle\SlackwareBundle\Entity\MemberRepository")
 */
class Member extends BaseUser
{
    const ROLE_ADMIN='ROLE_ADMIN';
    const ROLE_USER='ROLE_USER';
    const ROLE_MANAGER='ROLE_MANAGER';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        $this->created = new \DateTime();
        $this->modified = $this->created;
        $this->addRole(self::ROLE_USER);
    }

    /**
     * @Assert\NotBlank(message="用户名不可为空")
     * @Assert\Length(
     *     min="4",
     *     max="36",
     *     minMessage="用户名不能少于4个字符",
     *     maxMessage="用户名不能多于36个字符"
     * )
     * @Assert\Regex(
     *    pattern="/^[A-z0-9]*$/i",
     *    message="用户名只能使用英文字母和数字"
     * )
     */
    protected $username;


    /**
     * @Assert\Email(
     *    checkMX=true,
     *    message="请使用合法的电子信箱"
     * )
     */
    protected $email;

    /**
     *
     * @ORM\Column(name="nickname",type="string",length=255,nullable=true)
     * @Assert\NotBlank(message="昵称不可为空")
     * @Assert\Length(
     *     min="2",
     *     max="36",
     *     minMessage="昵称不能少于2个字符",
     *     maxMessage="昵称不能多于36个字符"
     * )
     */
    protected $nickname;

    /**
     * @ORM\Column(name="website", type="string",length=500, nullable=true)
     * @Assert\Url(message="请使用合法的URL地址")
     */
    protected $website;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\Length(
     *     max="400",
     *     maxMessage="个人介绍不能超过400个字"
     * )
     */
    protected $description;

    /**
     * @ORM\Column(name="twitter",type="string", length=255,nullable=true)
     * @assert\Length(
     *         max="255",
     *         maxMessage="不能超过255个字符"
     * )
     * @Assert\Url(
     *     message="请使用合法的URL"
     * )
     */
    protected $twitter;

    /**
     * @ORM\Column(name="googleplus",type="string",length=255,nullable=true)
     * @assert\Length(
     *         max="255",
     *         maxMessage="不能超过255个字符"
     * )
     * @Assert\Url(
     *     message="请使用合法的URL"
     * )
     */
    protected $googleplus;

    /**
     * @ORM\Column(name="facebook",type="string",length=255,nullable=true)
     * @assert\Length(
     *         max="255",
     *         maxMessage="不能超过255个字符"
     * )
     * @Assert\Url(message="请使用合法的URL")
     */
    protected $facebook;

    /**
     * @ORM\Column(name="weibo",type="string",length=255,nullable=true)
     * @assert\Length(
     *         max="255",
     *         maxMessage="不能超过255个字符"
     * )
     * @Assert\Url(message="请使用合法的URL")
     */
    protected $weibo;

    /**
     * @ORM\Column(name="github",type="string",length=255,nullable=true)
     * @assert\Length(
     *         max="255",
     *         maxMessage="不能超过255个字符"
     * )
     * @Assert\Url(message="请使用合法的URL")
     */
    protected $github;
    /**
     * @ORM\Column(name="city",type="string",length=60,nullable=true)
     * @Assert\Length(
     *    max="60",
     *    maxMessage="所在位置不能超过60个字"
     * )
     */
    protected $city;

    /**
     * @ORM\Column(name="css",type="text", nullable=true)
     * @Assert\Length(
     *      max="2500",
     *      maxMessage="自定义样式不能超过2500个字符"
     * )
     */
    protected $css;

    /**
     * @ORM\Column(type="string", length=255, name="avatar",nullable = true)
     *
     * @var string $imageName
     */
	private $avatar;
    
    /**
     * @Assert\File(
     *     maxSize="10M",
     *     mimeTypes={"image/png","image/jpeg","image/pjpeg",
	 *                          "image/jpg","image/gif"}
     * )
     * @Vich\UploadableField(mapping="avatar_image", fileNameProperty="avatar")
     *
     * @var File $image
     */
	private $image;

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
     * Set created
     *
     * @param \DateTime $created
     * @return Agent
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
     * @return Agent
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


	public function setAvatar($avatar)
	{
		$this->avatar = $avatar;
		return $this;
	}

	public function getAvatar()
	{
		return $this->avatar;
	}

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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set nickname
     *
     * @param string $nickname
     * @return Member
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
    
        return $this;
    }

    /**
     * Get nickname
     *
     * @return string 
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Member
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    
        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Member
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
     * Set twitter
     *
     * @param string $twitter
     * @return Member
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    
        return $this;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set googleplus
     *
     * @param string $googleplus
     * @return Member
     */
    public function setGoogleplus($googleplus)
    {
        $this->googleplus = $googleplus;
    
        return $this;
    }

    /**
     * Get googleplus
     *
     * @return string 
     */
    public function getGoogleplus()
    {
        return $this->googleplus;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     * @return Member
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    
        return $this;
    }

    /**
     * Get facebook
     *
     * @return string 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set weibo
     *
     * @param string $weibo
     * @return Member
     */
    public function setWeibo($weibo)
    {
        $this->weibo = $weibo;
    
        return $this;
    }

    /**
     * Get weibo
     *
     * @return string 
     */
    public function getWeibo()
    {
        return $this->weibo;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Member
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set css
     *
     * @param string $css
     * @return Member
     */
    public function setCss($css)
    {
        $this->css = $css;
    
        return $this;
    }

    /**
     * Get css
     *
     * @return string 
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * Set github
     *
     * @param string $github
     * @return Member
     */
    public function setGithub($github)
    {
        $this->github = $github;
    
        return $this;
    }

    /**
     * Get github
     *
     * @return string 
     */
    public function getGithub()
    {
        return $this->github;
    }
}