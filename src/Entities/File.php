<?php

namespace TapestryCloud\Database\Entities;

/**
 * @Entity
 * @Table(name="files")
 */
class File
{
    /** @Id @Column(type="integer") @GeneratedValue */
    private $id;

    /** @ManyToOne(targetEntity="Environment") */
    private $environment;

    /** @Column(type="string") */
    private $uid;

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
}