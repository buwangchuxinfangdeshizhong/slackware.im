<?php

namespace Slackiss\Bundle\SlackwareBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ItemCategory
 *
 * @ORM\Table(name="item_category")
 * @ORM\Entity(repositoryClass="Slackiss\Bundle\SlackwareBundle\Entity\ItemCategoryRepository")
 */
class ItemCategory
{
    public function __construct()
    {
        $this->created = new \DateTime();
        $this->modified = $this->created;
        $this->status = true;
        $this->enabled = true;
        $this->remark = "";
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
     * @Assert\NotBlank(message="知识库栏目名称不能为空")
     * @Assert\Length(max=255, maxMessage="知识库栏目名称不能超过255个字")
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="ItemCategory")
     * @ORM\JoinColumn(name="item_category_id",referencedColumnName="id",nullable=true)
     */
    private $parent;

    /**
     * @var string
     *
     * @ORM\Column(name="uid", type="string", length=255,unique=true)
     */
    private $uid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetimetz")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified", type="datetimetz")
     */
    private $modified;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @var string
     *
     * @ORM\Column(name="remark", type="text",nullable=true)
     */
    private $remark;


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
     * Set name
     *
     * @param string $name
     * @return ItemCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set parent
     *
     * @param string $parent
     * @return ItemCategory
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return ItemCategory
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return ItemCategory
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
     * @return ItemCategory
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
     * Set status
     *
     * @param boolean $status
     * @return ItemCategory
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return ItemCategory
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set remark
     *
     * @param string $remark
     * @return ItemCategory
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Get remark
     *
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }
}
