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
use TapestryCloud\Database\Entities\Classification;
use TapestryCloud\Database\Entities\ContentType;
use TapestryCloud\Database\Entities\File;
use TapestryCloud\Database\Entities\Taxonomy;

class PluginTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var EntityManager
     */
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
        $tool->dropDatabase();
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

    public static function runTapestry($siteDir = __DIR__ . DIRECTORY_SEPARATOR . 'mock_project') {
        $definitions = new DefaultInputDefinition();
        $tapestry = new Tapestry(new ArrayInput([
            '--site-dir' => $siteDir,
            '--env' => 'testing'
        ], $definitions));

        /** @var array $steps */
        $steps = $tapestry->getContainer()->get('Compile.Steps');
        $generator = new Generator($steps, $tapestry);

        /** @var Project $project */
        $project = $tapestry->getContainer()->get(Project::class);
        $generator->generate($project, new NullOutput());

        return $tapestry;
    }

    public function testPlugin(){
        self::runTapestry();

        $contentTypes = self::$em->getRepository(ContentType::class)->findAll();

        $this->assertCount(2, $contentTypes);

        /** @var ContentType $contentType */
        foreach ($contentTypes as $contentType) {
            $env = $contentType->getEnvironment();
            $this->assertEquals('testing', $env->getName());

            if ($contentType->getName() === 'blog'){
                $taxonomies = $contentType->getTaxonomy();
                $this->assertCount(2, $taxonomies);

                /** @var Taxonomy $taxonomy */
                foreach ($taxonomies as $taxonomy) {
                    if ($taxonomy->getName() === 'tag') {
                        $classifications = $taxonomy->getClassifications();
                        $this->assertCount(2, $classifications);
                    }
                }
            }
        }

        $files = self::$em->getRepository(File::class)->findAll();
        $this->assertCount(3, $files);

        $classifications = self::$em->getRepository(Classification::class)->findAll();
        $this->assertCount(4, $classifications);

        $n = 1;
    }
}