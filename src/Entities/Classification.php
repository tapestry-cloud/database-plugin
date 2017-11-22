<?php

namespace TapestryCloud\Database\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
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
     * @ManyToMany(targetEntity="Taxonomy", inversedBy="classification")
     * @JoinTable(
     *  name="taxonomy_classifications",
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
     * @ManyToMany(targetEntity="Classification")
     * @JoinTable(name="file_classifications",
     *      joinColumns={@JoinColumn(name="classification_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="file_id", referencedColumnName="id")}
     * )
     */
    private $files;

    public function __construct()
    {
        $this->taxonomy = new ArrayCollection();
        $this->files = new ArrayCollection();
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

}