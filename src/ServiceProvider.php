<?php

namespace TapestryCloud\Database;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Tapestry\Entities\Configuration;
use Tapestry\Entities\Project;
use Tapestry\Tapestry;

class ServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /** @var array */
    protected $provides = [
        Exporter::class
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
        $this->getContainer()->add(Exporter::class, function() {
            return new Exporter();
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
