<?php

declare(strict_types=1);

use LaravelProjectPatterns\Context\Catalog;
use LaravelProjectPatterns\Context\MarkdownGraph;
use LaravelProjectPatterns\Context\Resolver;

require_once __DIR__.'/bootstrap.php';
require_once __DIR__.'/tests/TestHarness.php';

$skillRoot = dirname(__DIR__);
$catalog = Catalog::load($skillRoot);
$graph = new MarkdownGraph($skillRoot);
$resolver = new Resolver($catalog, $graph);
$harness = new TestHarness(__DIR__.'/context.php');

require __DIR__.'/tests/CatalogCoverageTest.php';
require __DIR__.'/tests/FrontierCoverageTest.php';
require __DIR__.'/tests/InteractionCliTest.php';
require __DIR__.'/tests/InstructionContractTest.php';
require __DIR__.'/tests/ValidatorMutationTest.php';

$harness->finish();
