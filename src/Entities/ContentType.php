<?php

namespace TapestryCloud\Database\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Tapestry\Entities\ContentType as TapestryContentType;

/**
 * @Entity
 * @Table(name="content_types")
 */
class ContentType
{
    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    private $id;

    /**
     * @var Environment
     * @ManyToOne(targetEntity="Environment")
     */
    private $environment;

    /**
     * @var string
     * @Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @Column(type="string")
     */
    private $path;

    /**
     * @var string
     * @Column(type="string")
     */
    private $template;

    /**
     * @var string
     * @Column(type="string")
     */
    private $permalink;

    /**
     * @var bool
     * @Column(type="boolean")
     */
    private $enabled;

    /**
     * @var Collection|Taxonomy[]
     *
     * @OneToMany(targetEntity="Taxonomy", mappedBy="contentType")
     */
    private $taxonomy;

    /**
     * ContentType constructor.
     */
    public function __construct()
    {
        $this->taxonomy = new ArrayCollection();
    }

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
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param Environment $environment
     */
    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return Collection|Taxonomy[]
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    /**
     * @param Taxonomy $taxonomy
     */
    public function addTaxonomy(Taxonomy $taxonomy)
    {
        if ($this->taxonomy->contains($taxonomy)) {
            return;
        }

        $this->taxonomy->add($taxonomy);
        $taxonomy->setContentType($this);
    }

    /**
     * @param Taxonomy $taxonomy
     */
    public function removeTaxonomy(Taxonomy $taxonomy)
    {
        if (!$this->taxonomy->contains($taxonomy)) {
            return;
        }

        $this->taxonomy->removeElement($taxonomy);
    }
}