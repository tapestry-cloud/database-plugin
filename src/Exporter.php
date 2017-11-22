<?php

namespace TapestryCloud\Database;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PDO;
use Tapestry\Entities\Collections\FlatCollection;
use Tapestry\Entities\Project;
use Doctrine\DBAL\Connection;
use Tapestry\Modules\ContentTypes\ContentTypeFactory;
use TapestryCloud\Database\Entities\Environment;
use TapestryCloud\Database\Entities\File;

class Exporter {

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var EntityManagerInterface|EntityManager
     */
    private $entityManager;

    /**
     * Exporter constructor.
     * @param Connection $connection
     * @param EntityManagerInterface $entityManager
     * @throws \Exception
     */
    public function __construct(Connection $connection, EntityManagerInterface $entityManager)
    {
        $this->connection = $connection;
        if (!$this->connection->connect()) {
            throw new \Exception('Unable to connect to database');
        }

        $tables = $this->connection->getSchemaManager()->listTables();

        $migrator = new Migrator($this->connection);
        if (count($tables) < $migrator->tables()) {
            //$migrator->migrate();
        }

        $this->entityManager = $entityManager;
    }

    public function export(Project $project) {
        /** @var FlatCollection $files */
        $files = $project->get('files');

        /** @var ContentTypeFactory $contentTypes */
        $contentTypes = $project->get('content_types');

        /** @var array $cmdOptions */
        $cmdOptions = $project->get('cmd_options');

        // 1. Create env record if none found for current environment
        // $environment = new Environment();
        // $environment->name = $cmdOptions['env'];

        if (! $environment = $this->entityManager->getRepository(Environment::class)->findOneBy(['name' => $cmdOptions['env']])) {
            $environment = new Environment();
            $environment->setName($cmdOptions['env']);
            $this->entityManager->persist($environment);
            $this->entityManager->flush();
        }

        /** @var \Tapestry\Entities\File $file */
        foreach ($files as $file) {
            $fileRecord = new File();
            $fileRecord->setUid($file->getUid());
            $environment->addFile($fileRecord);
            $this->entityManager->persist($fileRecord);
        }

        $this->entityManager->flush();

    }
}