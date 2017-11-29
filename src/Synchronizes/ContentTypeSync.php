<?php

namespace TapestryCloud\Database\Synchronizes;

use Doctrine\ORM\EntityManagerInterface;
use Tapestry\Modules\ContentTypes\ContentTypeFactory;
use TapestryCloud\Database\Entities\ContentType;
use TapestryCloud\Database\Hydrators\ContentType as ContentTypeHydrator;
use TapestryCloud\Database\Hydrators\Taxonomy as TaxonomyHydrator;

class ContentTypeSync
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
            if (!$record = $this->em->getRepository(ContentType::class)->findOneBy(['name' => $contentType->getName()])) {
                $record = new ContentType();
            }

            $this->contentTypeHydrator->hydrate($record, $contentType);
            $this->em->persist($record);
        }
        $this->em->flush();
    }
}