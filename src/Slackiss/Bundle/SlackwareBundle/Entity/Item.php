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
}
