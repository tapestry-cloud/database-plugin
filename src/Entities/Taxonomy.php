<?php

namespace TapestryCloud\Database\Entities;

use Tapestry\Entities\Taxonomy as TapestryTaxonomy;

/**
 * @Entity
 * @Table(name="taxonomies")
 */
class Taxonomy
{
    /** @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /** @Column(type="string") */
    private $name;

    /** @OnetoMany(targetEntity="Classification", mappedBy="taxonomy_id") */
    private $classifications;

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
}