<?php

namespace TapestryCloud\Database\Entities;

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

    /** @ManyToOne(targetEntity="Taxonomy") */
    private $taxonomy;

    /**
     * @ManyToMany(targetEntity="Classification")
     * @JoinTable(name="files_classifications",
     *      joinColumns={@JoinColumn(name="file_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="classification_id", referencedColumnName="id")}
     * )
     */
    private $files;

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