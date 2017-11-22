<?php

namespace TapestryCloud\Database\Entities;

use Tapestry\Entities\ContentType as TapestryContentType;

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

    /** @OnetoMany(targetEntity="Taxonomy", mappedBy="content_type_id") */
    private $taxonomy;

    /**
     * ContentType Hydration
     *
     * @param TapestryContentType $contentType
     * @param Environment $environment
     */
    public function hydrate(TapestryContentType $contentType, Environment $environment)
    {
        $this->name = $contentType->getName();
        $this->path = $contentType->getPath();
        $this->template = $contentType->getTemplate();
        $this->permalink = $contentType->getPermalink();
        $this->enabled = $contentType->isEnabled();
        $this->setEnvironment($environment);
    }

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

    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return Taxonomy[]
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    public function addTaxonomy(Taxonomy $taxonomy)
    {
        $this->taxonomy[] = $taxonomy;
    }
}