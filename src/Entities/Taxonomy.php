<?php

namespace TapestryCloud\Database\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Tapestry\Entities\Taxonomy as TapestryTaxonomy;

/**
 * @Entity
 * @Table(name="taxonomies")
 */
class Taxonomy
{
    /**
     * @var int
     *
     * @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /**
     * @var string
     *
     * @Column(type="string") */
    private $name;


    /**
     * @var ContentType
     *
     * @ManyToOne(targetEntity="ContentType", inversedBy="taxonomy")
     */
    private $contentType;

    /**
     * @var Collection|Classification[]
     *
     * @ManyToMany(targetEntity="Classification", mappedBy="taxonomy")
     */
    private $classifications;

    /**
     * Taxonomy constructor.
     */
    public function __construct()
    {
        $this->classifications = new ArrayCollection();
    }

    /**
     * Taxonomy Hydration.
     * @param TapestryTaxonomy $taxonomy
     */
    public function hydrate(TapestryTaxonomy $taxonomy)
    {
        $this->name = $taxonomy->getName();
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

    public function getContentType()
    {
        return $this->contentType;
    }

    public function setContentType(ContentType $contentType) {
        $this->contentType = $contentType;
    }

    public function getClassifications()
    {
        return $this->classifications;
    }

    public function addClassification(Classification $classification)
    {

    }
}