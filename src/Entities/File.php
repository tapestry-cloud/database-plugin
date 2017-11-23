<?php

namespace TapestryCloud\Database\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Tapestry\Modules\Content\FrontMatter as TapestryFrontMatter;

/**
 * @Entity
 * @Table(name="files")
 */
class File
{
    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    private $id;

    /**
     * @var string
     * @Column(type="string")
     */
    private $uid;

    /**
     * @var int
     * @Column(type="integer")
     */
    private $lastModified;

    /**
     * @var string
     * @Column(type="string")
     */
    private $filename;

    /**
     * @var string
     * @Column(type="string")
     */
    private $ext;

    /**
     * @var string
     * @Column(type="string")
     */
    private $path;

    /**
     * @var string
     * @Column(type="text")
     */
    private $content;

    /**
     * @var bool
     * @Column(type="boolean")
     */
    private $toCopy;

    /**
     * @var Environment
     * @ManyToOne(targetEntity="Environment")
     */
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
     * @var Collection|FrontMatter[]
     * @OneToMany(targetEntity="FrontMatter", mappedBy="file_id")
     */
    private $frontMatter;

    /**
     * File constructor.
     */
    public function __construct()
    {
        $this->classifications = new ArrayCollection();
        $this->frontMatter = new ArrayCollection();
    }

    /**
     * File Hydration.
     *
     * @param \Tapestry\Entities\File $file
     * @param Environment|null $environment
     */
    public function hydrate(\Tapestry\Entities\File $file, Environment $environment = null)
    {
        $this->setUid($file->getUid());
        $this->setLastModified($file->getLastModified());
        $this->setFilename($file->getFilename());
        $this->setExt($file->getExt());
        $this->setPath($file->getPath());
        $this->setToCopy($file->isToCopy());

        if (! $file->isToCopy()) {
            $frontMatter = new TapestryFrontMatter($file->getFileContent());
            $this->setContent($frontMatter->getContent());
            foreach($frontMatter->getData() as $key => $value) {
                $fmRecord = new FrontMatter();
                $fmRecord->setName($key);
                $fmRecord->setValue($value);
                $this->addFrontMatter($fmRecord);
            }
        }

        if (!is_null($environment)) {
            $this->setEnvironment($environment);
        }
    }

    //
    // Relationships
    //

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

    public function addFrontMatter(FrontMatter $frontMatter) {
        if ($this->frontMatter->contains($frontMatter)) {
            return;
        }

        $this->frontMatter->add($frontMatter);
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

    //
    // Getters & Setters
    //

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
     * @return int
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * @param int $lastModified
     */
    public function setLastModified($lastModified)
    {
        $this->lastModified = $lastModified;
    }

    /**
     * @return bool
     */
    public function isToCopy()
    {
        return $this->toCopy;
    }

    /**
     * @param bool $toCopy
     */
    public function setToCopy($toCopy)
    {
        $this->toCopy = $toCopy;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @param string $ext
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}