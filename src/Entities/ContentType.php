<?php

namespace TapestryCloud\Database\Entities;

/**
 * @Entity
 * @Table(name="content_types")
 */
class ContentType
{
    /** @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /** @ManyToOne(targetEntity="Environment") */
    private $environment;

    /** @Column(type="string") */
    private $name;

    /** @Column(type="string") */
    private $path;

    /** @Column(type="string") */
    private $template;

    /** @Column(type="string") */
    private $permalink;

    /** @Column(type="boolean") */
    private $enabled;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @return string
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @return Environment[]
     */
    public function getEnvironment()
    {
        return $this->environment;
    }
}