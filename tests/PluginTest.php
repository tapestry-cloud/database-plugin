<?php

namespace TapestryCloud\Asset\Tests;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Tapestry\Console\DefaultInputDefinition;
use Tapestry\Entities\Configuration;
use Tapestry\Entities\Project;
use Tapestry\Generator;
use Tapestry\Tapestry;
use TapestryCloud\Database\Entities\Classification;
use TapestryCloud\Database\Entities\ContentType;
use TapestryCloud\Database\Entities\File;
use TapestryCloud\Database\Entities\FrontMatter;
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

    public static function runTapestry($env = 'testing', $siteDir = __DIR__ . DIRECTORY_SEPARATOR . 'mock_project')
    {
        $definitions = new DefaultInputDefinition();
        $tapestry = new Tapestry(new ArrayInput([
            '--site-dir' => $siteDir,
            '--env' => $env
        ], $definitions));

        $bufferedOutput = new BufferedOutput();

        $tapestry->setOutput($bufferedOutput);

        /** @var array $steps */
        $steps = $tapestry->getContainer()->get('Compile.Steps');
        $generator = new Generator($steps, $tapestry);

        /** @var Project $project */
        $project = $tapestry->getContainer()->get(Project::class);
        $generator->generate($project, $bufferedOutput);

        return $bufferedOutput;
    }

    /**
     * This tests that records are inserted and not duplicated when ran multiple times.
     */
    public function testNoChangeRunThrough()
    {
        $loops = 0;
        while ($loops < 2) {
            $output = self::runTapestry('testing');
            $this->assertContains('[$] Syncing with database.', $output->fetch());

            $contentTypes = self::$em->getRepository(ContentType::class)->findAll();
            $this->assertCount(2, $contentTypes);

            /** @var ContentType $contentType */
            foreach ($contentTypes as $contentType) {
                if ($contentType->getName() === 'blog') {
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

            $files = self::$em->getRepository(FrontMatter::class)->findAll();
            $this->assertCount(4, $files);

            $classifications = self::$em->getRepository(Classification::class)->findAll();
            $this->assertCount(4, $classifications);

            $contentTypes = self::$em->getRepository(ContentType::class)->findAll();
            $this->assertCount(2, $contentTypes);
            $loops++;
        }
    }

    /**
     * This tests
     */
    public function testDifferentEnvironmentRunThrough()
    {
        $output = self::runTapestry('testing-1');
        $this->assertContains('[$] Syncing with database.', $output->fetch());

        $files = self::$em->getRepository(File::class)->findAll();
        $this->assertCount(3, $files);

        $files = self::$em->getRepository(FrontMatter::class)->findAll();
        $this->assertCount(4, $files);

        $classifications = self::$em->getRepository(Classification::class)->findAll();
        $this->assertCount(4, $classifications);

        $contentTypes = self::$em->getRepository(ContentType::class)->findAll();
        $this->assertCount(2, $contentTypes);
    }
}