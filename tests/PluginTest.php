<?php
namespace TapestryCloud\Asset\Tests;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Tapestry\Console\DefaultInputDefinition;
use Tapestry\Entities\Configuration;
use Tapestry\Entities\Project;
use Tapestry\Generator;
use Tapestry\Tapestry;

class PluginTest extends \PHPUnit_Framework_TestCase
{

    protected static $em;

    /**
     * This method is called before the first test of this test class is run.
     *
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        $configuration = new Configuration(include __DIR__ . '/mock_project/config.php');

        $em = EntityManager::create(
        $configuration->get('plugins.database', []),
            Setup::createAnnotationMetadataConfiguration(
                [
                    realpath(__DIR__ . '/../src/Entities')
                ],
                true
            )
        );
        $tool = new SchemaTool($em);
        $tool->createSchema($em->getMetadataFactory()->getAllMetadata());

        self::$em = $em;
    }

    /**
     * This method is called after the last test of this test class is run.
     *
     * @inheritdoc
     */
    public static function tearDownAfterClass()
    {
        $tool = new SchemaTool(self::$em);
        $tool->dropDatabase();
    }

    public function testPlugin(){
        $definitions = new DefaultInputDefinition();
        $tapestry = new Tapestry(new ArrayInput([
            '--site-dir' => __DIR__ . DIRECTORY_SEPARATOR . 'mock_project',
            '--env' => 'testing'
        ], $definitions));

        /** @var array $steps */
        $steps = $tapestry->getContainer()->get('Compile.Steps');
        $generator = new Generator($steps, $tapestry);

        /** @var Project $project */
        $project = $tapestry->getContainer()->get(Project::class);
        $generator->generate($project, new NullOutput());

        /** @var Project $project */
        //$project = $tapestry->getContainer()->get(Project::class);

        $n = 1;
    }
}