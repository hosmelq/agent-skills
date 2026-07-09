<?php

declare(strict_types=1);

use LaravelProjectPatterns\Context\Catalog;
use LaravelProjectPatterns\Context\ContextException;
use LaravelProjectPatterns\Context\MarkdownGraph;
use LaravelProjectPatterns\Context\Validator;

require_once __DIR__.'/bootstrap.php';

$skillRoot = dirname(__DIR__);

try {
    $result = (new Validator(Catalog::load($skillRoot), new MarkdownGraph($skillRoot)))->validate();

    foreach ($result['metrics'] as $metric => $value) {
        fwrite(STDOUT, "{$metric}={$value}".PHP_EOL);
    }

    fwrite(STDOUT, 'errors='.count($result['errors']).PHP_EOL);

    foreach ($result['errors'] as $error) {
        fwrite(STDOUT, $error.PHP_EOL);
    }

    exit($result['errors'] === [] ? 0 : 1);
} catch (ContextException $exception) {
    fwrite(STDERR, 'validate: '.$exception->getMessage().PHP_EOL);
    exit($exception->exitCode);
}
