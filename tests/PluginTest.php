<?php
namespace TapestryCloud\Asset\Tests;
use Symfony\Component\Console\Input\ArrayInput;
use Tapestry\Console\DefaultInputDefinition;
use Tapestry\Tapestry;
class PluginTest extends \PHPUnit_Framework_TestCase
{
    public function testPlugin(){
        //
        // The asset helper makes use of the `use` helper function which needs Tapestry loading
        // for it to work. Therefore we init Tapestry with a mock project folder.
        //
        $definitions = new DefaultInputDefinition();
        $tapestry = new Tapestry(new ArrayInput([
            '--site-dir' => __DIR__ . DIRECTORY_SEPARATOR . 'mock_project',
            '--env' => 'testing'
        ], $definitions));

        $n = 1;
    }
}