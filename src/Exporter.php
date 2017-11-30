<?php

namespace TapestryCloud\Database;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tapestry\Entities\Collections\FlatCollection;
use Tapestry\Entities\Project;
use Tapestry\Modules\ContentTypes\ContentTypeFactory;
use Tapestry\Tapestry;
use TapestryCloud\Database\Synchronizes\ContentTypeSync;
use TapestryCloud\Database\Synchronizes\FileSync;
use TapestryCloud\Database\Hydrators\File as FileHydrator;
use TapestryCloud\Database\Hydrators\ContentType as ContentTypeHydrator;
use TapestryCloud\Database\Hydrators\Taxonomy as TaxonomyHydrator;
use TapestryCloud\Database\Synchronizes\TaxonomySync;

class Exporter
{
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
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __construct(EntityManagerInterface $entityManager, Tapestry $tapestry)
    {
        $this->entityManager = $entityManager;
        $this->output = $tapestry->getContainer()->get(OutputInterface::class);

    }

    /**
     * @param Project $project
     * @throws \Exception
     */
    public function export(Project $project)
    {

        $this->output->writeln('[$] Syncing with database.');

        /** @var FlatCollection $files */
        $files = $project->get('files');

        /** @var ContentTypeFactory $contentTypes */
        $contentTypes = $project->get('content_types');

        /** @var array $cmdOptions */
        $cmdOptions = $project->get('cmd_options');

        // 1. Sync Content Types Base
        $contentTypeSync = new ContentTypeSync(
            $this->entityManager,
            new ContentTypeHydrator($this->entityManager),
            new TaxonomyHydrator($this->entityManager)
        );
        $contentTypeSync->sync($contentTypes);

        // 2. Sync Files
        $fileSync = new FileSync(
            $this->entityManager,
            new FileHydrator($this->entityManager)
        );
        $fileSync->sync($files);

        // 3. Sync Taxonomy foreach Content Type
        // 4. Sync Classifications foreach Taxonomy - attaching Files

        $taxonomySync = new TaxonomySync(
            $this->entityManager,
            new ContentTypeHydrator($this->entityManager),
            new TaxonomyHydrator($this->entityManager)
        );
        $taxonomySync->sync($contentTypes);
    }
}