<?php

namespace TapestryCloud\Database\Entities;

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