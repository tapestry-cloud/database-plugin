<?php

namespace TapestryCloud\Database\Entities;

/**
 * @Entity
 * @Table(name="frontmatter")
 */
class FrontMatter
{
    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /**
     * @var string
     * @Column(type="string") */
    private $name;

    /**
     * @var string
     * @Column(type="string") */
    private $value;

    /** @ManyToOne(targetEntity="File") */
    private $file;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file
     */
    public function setFile(File $file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}