<?php

namespace TapestryCloud\Database\Synchronizes;

use Doctrine\ORM\EntityManagerInterface;
use Tapestry\Entities\Collections\FlatCollection;
use Tapestry\Entities\File as TapestryFile;
use TapestryCloud\Database\Entities\Environment;
use TapestryCloud\Database\Entities\File;

class Files
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ContentTypes constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function sync(FlatCollection $files, Environment $environment) {

        /** @var TapestryFile $file */
        foreach ($files as $file) {

            if (!$record = $this->em->getRepository(File::class)->findOneBy(['uid' => $file->getuid(), 'environment' => $environment->getId()])) {
                // INSERT
                $record = new File();
                $record->hydrate($file, $environment);
            }

            // UPDATE

            $this->em->persist($record);
        }

        $this->em->flush();
    }
}