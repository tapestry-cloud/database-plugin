<?php

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Input\ArrayInput;
use Tapestry\Console\DefaultInputDefinition;
use Tapestry\Entities\Configuration;
use Tapestry\Tapestry;

// replace with file to your own project bootstrap
require_once 'vendor/autoload.php';

$definitions = new DefaultInputDefinition();
$tapestry = new Tapestry(new ArrayInput([
    '--site-dir' => __DIR__ . DIRECTORY_SEPARATOR . 'mock_project',
    '--env' => 'cli-config'
], $definitions));

$tapestry->register(\TapestryCloud\Database\ServiceProvider::class);

/** @var Configuration $configuration */
$configuration = $tapestry->getContainer()->get(Configuration::class);
$configuration->merge(include __DIR__ . '/tests/mock_project/config.php');

return ConsoleRunner::createHelperSet($tapestry->getContainer()->get(EntityManagerInterface::class));