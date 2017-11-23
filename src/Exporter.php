<?php

namespace TapestryCloud\Database;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PDO;
use Symfony\Component\Console\Output\OutputInterface;
use Tapestry\Entities\Collections\FlatCollection;
use Tapestry\Entities\Project;
use Doctrine\DBAL\Connection;
use Tapestry\Modules\ContentTypes\ContentTypeFactory;
use Tapestry\Tapestry;
use TapestryCloud\Database\Entities\ContentType;
use TapestryCloud\Database\Entities\Environment;
use TapestryCloud\Database\Entities\File;
use TapestryCloud\Database\Synchronizes\ContentTypes;
use TapestryCloud\Database\Synchronizes\Files;
use TapestryCloud\Database\Hydrators\File as FileHydrator;

class Exporter {
    /**
     * @var EntityManagerInterface|EntityManager
     */
    private $entityManager;
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * Exporter constructor.
     * @param EntityManagerInterface $entityManager
     * @param Tapestry $tapestry
     */
    public function __construct(EntityManagerInterface $entityManager, Tapestry $tapestry)
    {
        $this->entityManager = $entityManager;
        $this->output = $tapestry->getContainer()->get(OutputInterface::class);

    }

    public function export(Project $project) {

        $this->output->writeln('[$] Syncing with database.');

        /** @var FlatCollection $files */
        $files = $project->get('files');

        /** @var ContentTypeFactory $contentTypes */
        $contentTypes = $project->get('content_types');

        /** @var array $cmdOptions */
        $cmdOptions = $project->get('cmd_options');

        if (! $environment = $this->entityManager->getRepository(Environment::class)->findOneBy(['name' => $cmdOptions['env']])) {
            $environment = new Environment();
            $environment->setName($cmdOptions['env']);
            $this->entityManager->persist($environment);
            $this->entityManager->flush();
        }

        $fileSync = new Files($this->entityManager, new FileHydrator($this->entityManager));
        $fileSync->sync($files, $environment);

        $contentTypeSync = new ContentTypes($this->entityManager);
        $contentTypeSync->sync($contentTypes, $environment);
    }
}