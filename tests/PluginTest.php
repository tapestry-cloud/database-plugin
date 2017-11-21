<?php
namespace TapestryCloud\Asset\Tests;
use Symfony\Component\Console\Input\ArrayInput;
use Tapestry\Console\DefaultInputDefinition;
use Tapestry\Entities\Project;
use Tapestry\Tapestry;
class PluginTest extends \PHPUnit_Framework_TestCase
{
    public function testPlugin(){
        $definitions = new DefaultInputDefinition();
        $tapestry = new Tapestry(new ArrayInput([
            '--site-dir' => __DIR__ . DIRECTORY_SEPARATOR . 'mock_project',
            '--env' => 'testing'
        ], $definitions));



        /** @var Project $project */
        $project = $tapestry->getContainer()->get(Project::class);

        $n = 1;
    }
}