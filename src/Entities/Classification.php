<?php

namespace TapestryCloud\Database\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use TapestryCloud\Database\Repositories\ClassificationRepository;

/**
 * @Entity(repositoryClass="TapestryCloud\Database\Repositories\ClassificationRepository")
 * @Table(name="classifications")
 */
class Classification
{
    /** @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /** @Column(type="string") */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection|Taxonomy[]
     *
     * @ManyToMany(targetEntity="Taxonomy", inversedBy="classification",cascade={"persist"})
     * @JoinTable(
     *  name="classifications_taxonomy",
     *  joinColumns={
     *      @JoinColumn(name="classification_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @JoinColumn(name="taxonomy_id", referencedColumnName="id")
     *  }
     * )
     */
    private $taxonomy;

    /**
     * @var \Doctrine\Common\Collections\Collection|File[]
     *
     * @ManyToMany(targetEntity="File", mappedBy="classifications")
     */
    private $files;

    public function __construct()
    {
        $this->taxonomy = new ArrayCollection();
        $this->files = new ArrayCollection();
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
     * @param Taxonomy $taxonomy
     */
    public function addTaxonomy(Taxonomy $taxonomy)
    {
        if ($this->taxonomy->contains($taxonomy)) {
            return;
        }

        $this->taxonomy->add($taxonomy);
        $taxonomy->addClassification($this);
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

    /**
     * @return \Doctrine\Common\Collections\Collection|Taxonomy[]
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|File[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param ClassificationRepository $repository
     * @param ContentType $contentType
     * @return File[]
     */
    public function findFilesByContentType(ClassificationRepository $repository, ContentType $contentType)
    {
        return $repository->findFilesByContentType($contentType, $this);
    }

}