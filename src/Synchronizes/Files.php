<?php

namespace TapestryCloud\Database\Synchronizes;

use Doctrine\ORM\EntityManagerInterface;
use Tapestry\Entities\Collections\FlatCollection;
use Tapestry\Entities\File as TapestryFile;
use TapestryCloud\Database\Entities\Environment;
use TapestryCloud\Database\Entities\File;
use TapestryCloud\Database\Hydrators\File as FileHydrator;

class Files
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

    public function sync(FlatCollection $files, Environment $environment) {

        /** @var TapestryFile $file */
        foreach ($files as $file) {

            if (!$record = $this->em->getRepository(File::class)->findOneBy(['uid' => $file->getuid(), 'environment' => $environment->getId()])) {
                $record = new File();
            }

            $this->fileHydrator->hydrate($record, $file, $environment);
            $this->em->persist($record);
        }

        $this->em->flush();
    }
}