<?php

namespace TapestryCloud\Database\Synchronizes;

use Doctrine\ORM\EntityManagerInterface;
use Tapestry\Entities\Taxonomy as TapestryTaxonomy;
use Tapestry\Modules\ContentTypes\ContentTypeFactory;
use TapestryCloud\Database\Entities\Classification;
use TapestryCloud\Database\Entities\ContentType;
use TapestryCloud\Database\Entities\File;
use TapestryCloud\Database\Entities\Taxonomy;
use TapestryCloud\Database\Hydrators\ContentType as ContentTypeHydrator;
use TapestryCloud\Database\Hydrators\Taxonomy as TaxonomyHydrator;

class TaxonomySync
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ContentTypeHydrator
     */
    private $contentTypeHydrator;

    /**
     * @var TaxonomyHydrator
     */
    private $taxonomyHydrator;

    /**
     * ContentTypes constructor.
     * @param EntityManagerInterface $em
     * @param ContentTypeHydrator $contentTypeHydrator
     * @param TaxonomyHydrator $taxonomyHydrator
     */
    public function __construct(
        EntityManagerInterface $em,
        ContentTypeHydrator $contentTypeHydrator,
        TaxonomyHydrator $taxonomyHydrator
    ){
        $this->em = $em;
        $this->contentTypeHydrator = $contentTypeHydrator;
        $this->taxonomyHydrator = $taxonomyHydrator;
    }

    public function sync(ContentTypeFactory $contentTypeFactory)
    {
        foreach ($contentTypeFactory->all() as $contentType) {
            /** @var ContentType $record */
            if (!$record = $this->em->getRepository(ContentType::class)->findOneBy(['name' => $contentType->getName()])) {
                throw new \Exception('The content type ['. $contentType->getName() .'] has not been seeded.');
            }

            $this->syncTaxonomyToContentType($record, $contentType->getTaxonomies());
        }
        $this->em->flush();
    }

    /**
     * @param ContentType $contentType
     * @param TapestryTaxonomy[] $taxonomies
     */
    private function syncTaxonomyToContentType(ContentType $contentType, array $taxonomies)
    {
        /** @var TapestryTaxonomy $taxonomy */
        foreach ($taxonomies as $taxonomy) {
            if (!$record = $this->em->getRepository(Taxonomy::class)->findOneBy(['name' => $taxonomy->getName(), 'contentType' => $contentType])) {
                $record = new Taxonomy();
            }

            $this->taxonomyHydrator->hydrate($record, $taxonomy);
            $contentType->addTaxonomy($record);

            //$this->em->persist($contentType);

            foreach ($taxonomy->getFileList() as $classification => $files) {
                if (!$classificationRecord = $this->em->getRepository(Classification::class)->findOneBy(['name' => $classification])) {
                    $classificationRecord = new Classification();
                    $classificationRecord->setName($classification);
                    //$this->em->persist($classificationRecord);
                }

                $classificationRecord->addTaxonomy($record);

                foreach (array_keys($files) as $filename) {
                    /** @var File $file */
                    if ($file = $this->em->getRepository(File::class)->findOneBy(['uid' => $filename])) {
                        $file->addClassification($classificationRecord);
                        //$this->em->persist($file);
                    }
                }
            }
        }
        $this->em->flush();
    }
}