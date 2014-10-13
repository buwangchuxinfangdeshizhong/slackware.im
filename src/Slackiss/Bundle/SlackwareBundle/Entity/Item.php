<?php

namespace Slackiss\Bundle\SlackwareBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="Slackiss\Bundle\SlackwareBundle\Entity\ItemRepository")
 */
class Item
{

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->modified = $this->created;
        $this->status = true;
        $this->enabled = true;
        $this->remark = "";
        $this->version = 1;
        $this->last = true;
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
     * @ORM\ManyToOne(targetEntity="ItemCategory")
     * @ORM\JoinColumn(name="category_id",referencedColumnName="id")
     */
    private $category;

    /**
     * @var string
     * @Assert\NotBlank(message="请输入标题")
     * @Assert\Length(max=500, maxMessage="标题不能超过500个字")
     * @ORM\Column(name="title", type="string", length=500)
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank(message="请输入内容")
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="version", type="integer")
     */
    private $version;

    /**
     * 设置当前Item是否为最新版本，以方便检索
     * @ORM\Column(name="last", type="boolean")
     */
    private $last;

    /**
     * 第一个Item，则永远保持top为空，并且version==1
     * 所有version大于1的Item，其top全部指向top=null的Item
     * @ORM\ManyToOne(targetEntity="Item")
     * @ORM\JoinColumn(name="top_id",referencedColumnName="id",nullable=true)
     */
    private $top;
    /**
     * @var string
     * @Assert\NotBlank(message="请输入知识库变更日志")
     * @ORM\Column(name="changelog", type="text")
     */
    private $changelog;


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
     * Set category
     *
     * @param string $category
     * @return Item
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Item
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
     * @return Item
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
     * Set version
     *
     * @param integer $version
     * @return Item
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return integer
     */
    public function getVersion()
    {
        return $this->version;
    }

    public function updateVersion()
    {
        $this->version = $this->version+1;
    }

    /**
     * Set changelog
     *
     * @param string $changelog
     * @return Item
     */
    public function setChangelog($changelog)
    {
        $this->changelog = $changelog;

        return $this;
    }

    /**
     * Get changelog
     *
     * @return string
     */
    public function getChangelog()
    {
        return $this->changelog;
    }

    /**
     * Set last
     *
     * @param boolean $last
     * @return Item
     */
    public function setLast($last)
    {
        $this->last = $last;

        return $this;
    }

    /**
     * Get last
     *
     * @return boolean
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * Set top
     *
     * @param \Slackiss\Bundle\SlackwareBundle\Entity\Item $top
     * @return Item
     */
    public function setTop(\Slackiss\Bundle\SlackwareBundle\Entity\Item $top = null)
    {
        $this->top = $top;

        return $this;
    }

    /**
     * Get top
     *
     * @return \Slackiss\Bundle\SlackwareBundle\Entity\Item
     */
    public function getTop()
    {
        return $this->top;
    }
}
