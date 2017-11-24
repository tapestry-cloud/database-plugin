<?php

namespace TapestryCloud\Database\Repositories;

use Doctrine\ORM\EntityRepository;
use TapestryCloud\Database\Entities\File;

class ClassificationRepository extends EntityRepository
{
    /**
     * @param int $contentTypeId
     * @param int $classificationId
     * @return File[]
     */
    public function findFilesByContentType($contentTypeId, $classificationId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->from(File::class, 'f')
            ->select(['f'])
            ->leftJoin('classification_files', 'cf')
            ->where('cf.file_id = f.id')
            ->where('cf.classification_id = ?1')
            ->where('f.contentType_id = ?2')
            ->setParameter(1, $classificationId)
            ->setParameter(2, $contentTypeId);

        $query = $qb->getQuery();
        return $query->getResult();
    }

}