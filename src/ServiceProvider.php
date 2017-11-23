<?php

namespace TapestryCloud\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Tapestry\Entities\Configuration;
use Tapestry\Entities\Project;
use Tapestry\Tapestry;

class ServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /** @var array */
    protected $provides = [
        EntityManagerInterface::class,
        Connection::class
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {

        $this->getContainer()->add(Connection::class, function () {
            /** @var Configuration $configuration */
            $configuration = $this->getContainer()->get(Configuration::class);
            return DriverManager::getConnection($configuration->get('plugins.database', []), new \Doctrine\DBAL\Configuration());
        });

        $this->getContainer()->add(EntityManagerInterface::class, function () {
            /** @var Configuration $configuration */
            $configuration = $this->getContainer()->get(Configuration::class);

            return EntityManager::create(
                $configuration->get('plugins.database', []),
                Setup::createAnnotationMetadataConfiguration(
                    [
                        realpath(__DIR__ . DIRECTORY_SEPARATOR . 'Entities')
                    ],
                    $configuration->get('debug', false)
                )
            );
        });
    }

    /**
     * Method will be invoked on registration of a service provider implementing
     * this interface. Provides ability for eager loading of Service Providers.
     *
     * @return void
     * @throws \Exception
     */
    public function boot()
    {
        /** @var Tapestry $tapestry */
        $tapestry = $this->getContainer()->get(Tapestry::class);
        /** @var Project $project */
        $project = $this->getContainer()->get(Project::class);

        $tapestry->getEventEmitter()->addListener('loadsourcefiles.after', function () use ($project) {
            /** @var Exporter $exporter */
            $exporter = $this->getContainer()->get(Exporter::class);
            $exporter->export($project);
        });
    }
}
