<?php

namespace TapestryCloud\Database\Entities;

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
     * @var string
     * @Column(type="string") */
    private $uid;

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
}