<?php

namespace TapestryCloud\Database\Repositories;

use Doctrine\ORM\EntityRepository;
use TapestryCloud\Database\Entities\Classification;
use TapestryCloud\Database\Entities\File;
use TapestryCloud\Database\Entities\ContentType;

class ClassificationRepository extends EntityRepository
{
    /**
     * @param ContentType $contentType
     * @param Classification $classification
     * @return File[]
     */
    public function findFilesByContentType(ContentType $contentType, Classification $classification)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->from(File::class, 'f')
            ->select(['f'])
            ->leftJoin('classification_files', 'cf')
            ->where('cf.file_id = f.id')
            ->where('cf.classification_id = ?1')
            ->where('f.contentType_id = ?2')
            ->setParameter(1, $classification->getId())
            ->setParameter(2, $contentType->getId());

        $query = $qb->getQuery();
        return $query->getResult();
    }

}