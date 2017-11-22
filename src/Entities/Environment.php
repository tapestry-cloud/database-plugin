<?php

namespace TapestryCloud\Database\Entities;

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

    /** @OneToMany(targetEntity="ContentType", mappedBy="environment_id") */
    private $contentTypes;

    /** @OneToMany(targetEntity="File", mappedBy="environment_id") */
    private $files;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getContentTypes()
    {
        return $this->contentTypes;
    }

    public function addContentType(ContentType $contentType) {
        $this->contentTypes[] = $contentType;
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function addFile(File $file) {
        $this->files[] = $file;
    }
}