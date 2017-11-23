<?php

namespace TapestryCloud\Database\Hydrators;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class Hydrator
{
    /**
     * @var EntityManagerInterface|EntityManager
     */
    protected $entityManager;


    /**
     * File constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}