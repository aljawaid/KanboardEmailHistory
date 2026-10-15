<?php

// phpcs:ignoreFile PSR1.Files.SideEffects.FoundWithSymbols -- This executable test bootstrap must define Kanboard constants.

/**
 * Runs KanboardEmailHistory's focused production regression checks against Kanboard.
 *
 * Related test files: TestAssertions.php and every *Test.php file in this directory.
 */
$kanboardRoot = isset($argv[1]) ? realpath($argv[1]) : false;

if ($kanboardRoot === false || !is_file($kanboardRoot . '/app/common.php')) {
    fwrite(STDERR, "Usage: php tests/run.php <kanboard-root>\n");
    exit(2);
}

$pluginRoot = dirname(__DIR__);
$pluginsRoot = getenv('KANBOARD_PLUGINS_DIR') ?: $kanboardRoot . '/plugins';

define('PLUGINS_DIR', $pluginsRoot);
define('DB_FILENAME', ':memory:');

require $kanboardRoot . '/app/common.php';
require __DIR__ . '/TestAssertions.php';

$pluginName = 'KanboardEmailHistory';
$loadedPlugins = $container['pluginLoader']->getPlugins();
$incompatiblePlugins = $container['pluginLoader']->getIncompatiblePlugins();

kanboardEmailHistoryAssertFalse(isset($incompatiblePlugins[$pluginName]), 'KanboardEmailHistory must be compatible with the tested Kanboard release.');
kanboardEmailHistoryAssertTrue(isset($loadedPlugins[$pluginName]), 'KanboardEmailHistory must load before its regression checks run.');

$testFiles = glob(__DIR__ . '/*Test.php');
sort($testFiles);

foreach ($testFiles as $testFile) {
    require $testFile;
}

echo 'KanboardEmailHistory regression checks passed on Kanboard ' . APP_VERSION . ' with PHP ' . PHP_VERSION . PHP_EOL;