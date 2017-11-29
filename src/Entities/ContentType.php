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
     * @OneToMany(targetEntity="Taxonomy", mappedBy="contentType", cascade={"persist"})
     */
    private $taxonomy;

    /**
     * @var \Doctrine\Common\Collections\Collection|File[]
     *
     * @OneToMany(targetEntity="File", mappedBy="contentType")
     */
    private $files;

    /**
     * ContentType constructor.
     */
    public function __construct()
    {
        $this->taxonomy = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    /**
     * ContentType Hydration
     *
     * @param TapestryContentType $contentType
     */
    public function hydrate(TapestryContentType $contentType)
    {
        $this->name = $contentType->getName();
        $this->path = $contentType->getPath();
        $this->template = $contentType->getTemplate();
        $this->permalink = $contentType->getPermalink();
        $this->enabled = $contentType->isEnabled();
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

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
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

    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param File $file
     */
    public function addFile(File $file)
    {
        if ($this->files->contains($file)) {
            return;
        }

        $this->files->add($file);
        //$file->setContentType($this);
    }

    /**
     * @param File $file
     */
    public function removeFile(File $file)
    {
        if (!$this->files->contains($file)) {
            return;
        }

        $this->files->removeElement($file);
    }

}