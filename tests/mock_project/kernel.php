<?php

namespace Tests\MockProject;

use Tapestry\Modules\Kernel\KernelInterface;
use TapestryCloud\Database\ServiceProvider;
use Tapestry\Tapestry;

class Kernel implements KernelInterface
{
    /**
     * @var Tapestry
     */
    private $tapestry;

    /**
     * Kernel constructor.
     * @param Tapestry $tapestry
     */
    public function __construct(Tapestry $tapestry)
    {
        $this->tapestry = $tapestry;
    }

    /**
     * This method is executed by Tapestry when the Kernel is registered.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }

    /**
     * This method of executed by Tapestry as part of the build process.
     *
     * @return void
     */
    public function boot()
    {
        $this->tapestry->register(ServiceProvider::class);
    }
}