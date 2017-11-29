<?php

namespace TapestryCloud\Database\Synchronizes;

use Doctrine\ORM\EntityManagerInterface;
use Tapestry\Entities\Collections\FlatCollection;
use Tapestry\Entities\File as TapestryFile;
use TapestryCloud\Database\Entities\ContentType;
use TapestryCloud\Database\Entities\File;
use TapestryCloud\Database\Hydrators\File as FileHydrator;

class FileSync
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var FileHydrator
     */
    private $fileHydrator;

    /**
     * ContentTypes constructor.
     * @param EntityManagerInterface $em
     * @param FileHydrator $fileHydrator
     */
    public function __construct(EntityManagerInterface $em, FileHydrator $fileHydrator)
    {
        $this->em = $em;
        $this->fileHydrator = $fileHydrator;
    }

    public function sync(FlatCollection $files)
    {
        /** @var TapestryFile $file */
        foreach ($files as $file) {
            $contentTypeName = $file->getData('contentType', 'default');
            if (!$contentType = $this->em->getRepository(ContentType::class)->findOneBy(['name' => $contentTypeName])) {
                throw new \Exception('The content type ['. $contentTypeName .'] has not been seeded.');
            }

            if (!$record = $this->em->getRepository(File::class)->findOneBy(['uid' => $file->getuid()])) {
                $record = new File();
            }

            $this->fileHydrator->hydrate($record, $file, $contentType);
            $this->em->persist($record);
        }

        $this->em->flush();
    }
}