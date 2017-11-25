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

        $qb->select('f')
            ->from(File::class, 'f')
            ->innerJoin('f.classifications', 'c', 'WITH', 'c.id = :classificationId')
            ->where('f.contentType = :contentTypeId')
            ->setParameter('classificationId', $classificationId)
            ->setParameter('contentTypeId', $contentTypeId);

        $query = $qb->getQuery();
        return $query->getResult();
    }

}