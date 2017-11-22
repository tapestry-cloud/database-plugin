<?php

namespace TapestryCloud\Database\Entities;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @Entity
 * @Table(name="files")
 */
class File
{
    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /**
     * @var Environment
     * @ManyToOne(targetEntity="Environment") */
    private $environment;

    /**
     * @var Collection|Classification[]
     *
     * @ManyToMany(targetEntity="Classification", inversedBy="files")
     * @JoinTable(
     *  name="classifications_files",
     *  joinColumns={
     *      @JoinColumn(name="file_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @JoinColumn(name="classification_id", referencedColumnName="id")
     *  }
     * )
     */
    private $classifications;

    /**
     * @var string
     * @Column(type="string") */
    private $uid;

    /**
     * File constructor.
     */
    public function __construct()
    {
        $this->classifications = new ArrayCollection();
    }

    /**
     * File Hydration.
     *
     * @param \Tapestry\Entities\File $file
     * @param Environment|null $environment
     */
    public function hydrate(\Tapestry\Entities\File $file, Environment $environment = null) {
        $this->uid = $file->getUid();

        if (!is_null($environment)) {
            $this->setEnvironment($environment);
        }
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
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
     * @param Classification $classification
     */
    public function addClassification(Classification $classification)
    {
        if ($this->classifications->contains($classification)) {
            return;
        }

        $this->classifications->add($classification);
    }

    /**
     * @param Classification $classification
     */
    public function removeClassification(Classification $classification)
    {
        if (!$this->classifications->contains($classification)) {
            return;
        }

        $this->classifications->removeElement($classification);
    }
}