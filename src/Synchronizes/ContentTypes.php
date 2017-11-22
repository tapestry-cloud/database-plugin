<?php

namespace TapestryCloud\Database\Synchronizes;

use Doctrine\ORM\EntityManagerInterface;
use Tapestry\Entities\Taxonomy as TapestryTaxonomy;
use Tapestry\Modules\ContentTypes\ContentTypeFactory;
use TapestryCloud\Database\Entities\Classification;
use TapestryCloud\Database\Entities\ContentType;
use TapestryCloud\Database\Entities\Environment;
use TapestryCloud\Database\Entities\File;
use TapestryCloud\Database\Entities\Taxonomy;

class ContentTypes
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

    public function sync(ContentTypeFactory $contentTypeFactory, Environment $environment) {
        foreach($contentTypeFactory->all() as $contentType) {
            if (!$record = $this->em->getRepository(ContentType::class)->findOneBy(['name' => $contentType->getName(), 'environment' => $environment->getId()])){
                // INSERT
                $record = new ContentType();
                $record->hydrate($contentType, $environment);
                $this->em->persist($record);
                $this->em->flush();

                $this->syncTaxonomyToContentType($record, $contentType->getTaxonomies(), $environment);
                continue;
            }

            // UPDATE
        }
    }

    /**
     * @param ContentType $contentType
     * @param TapestryTaxonomy[] $taxonomies
     * @param Environment $environment
     */
    private function syncTaxonomyToContentType(ContentType $contentType, array $taxonomies, Environment $environment) {

        /** @var TapestryTaxonomy $taxonomy */
        foreach($taxonomies as $taxonomy) {
            if (!$record = $this->em->getRepository(Taxonomy::class)->findOneBy(['name' => $taxonomy->getName()])){
                $record = new Taxonomy();
                $record->hydrate($taxonomy);
                $this->em->persist($record);
                $this->em->flush();

                $contentType->addTaxonomy($record);
                $this->em->persist($contentType);
                $this->em->flush();

                foreach ($taxonomy->getFileList() as $classification => $files) {
                    if (! $classificationRecord = $this->em->getRepository(Classification::class)->findOneBy(['name' => $classification])){
                        $classificationRecord = new Classification();
                        $classificationRecord->setName($classification);
                        $this->em->persist($classificationRecord);
                    }

                    $classificationRecord->addTaxonomy($record);
                    $this->em->flush();

                    foreach(array_keys($files) as $filename) {
                        /** @var File $file */
                        if ($file = $this->em->getRepository(File::class)->findOneBy(['uid' => $filename, 'environment' => $environment->getId()])){
                            $file->addClassification($classificationRecord);
                            $this->em->flush();
                        }
                    }

                }
            }
        }
    }
}