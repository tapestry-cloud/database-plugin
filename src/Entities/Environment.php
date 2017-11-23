<?php

namespace TapestryCloud\Database\Entities;

use Doctrine\Common\Collections\Collection;

/**
 * @Entity
 * @Table(name="environments")
 */
class Environment
{
    /** @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /** @Column(type="string") */
    private $name;

    /** @OneToMany(targetEntity="ContentType", mappedBy="environment") */
    private $contentTypes;

    /** @OneToMany(targetEntity="File", mappedBy="environment") */
    private $files;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Collection|ContentType[]
     */
    public function getContentTypes()
    {
        return $this->contentTypes;
    }

    public function addContentType(ContentType $contentType)
    {
        $this->contentTypes[] = $contentType;
    }

    /**
     * @return Collection|File[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    public function addFile(File $file)
    {
        $this->files[] = $file;
    }
}
