<?php

$mode = isset($argv[1]) ? $argv[1] : 'xml';

$classes = array(
    'PsContactinfoCacheHookTest',
    'PsContactinfoConstructorTest',
    'PsContactinfoGetContentTest',
    'PsContactinfoInstallTest',
    'PsContactinfoLoadGuardTest',
    'PsContactinfoRenderWidgetTest',
    'PsContactinfoTemplateContractTest',
    'PsContactinfoUpgradeTest',
    'PsContactinfoWidgetVariablesTest',
);

if ($mode === 'shuffled') {
    if (!class_exists('PHPUnit_Framework_TestSuite', false)) {
        require '/usr/local/bin/phpunit.phar';
    }
    require __DIR__ . '/bootstrap.php';
    foreach (glob(__DIR__ . '/PsContactinfo*Test.php') as $testFile) {
        require_once $testFile;
    }

    srand(20261008);
    shuffle($classes);

    $suite = new PHPUnit_Framework_TestSuite('ShuffledPsContactinfo');
    foreach ($classes as $className) {
        $suite->addTestSuite(new PHPUnit_Framework_TestSuite($className));
    }

    $runner = new PHPUnit_TextUI_TestRunner();
    $result = $runner->doRun($suite, array(
        'convertErrorsToExceptions' => true,
        'convertNoticesToExceptions' => true,
        'convertWarningsToExceptions' => true,
        'colors' => true,
    ));
    exit($result->wasSuccessful() ? 0 : 1);
}

passthru(
    'php -d display_errors=1 -d error_reporting=32767 /usr/local/bin/phpunit.phar -c ' .
    escapeshellarg(__DIR__ . '/phpunit.xml'),
    $exitCode
);
exit((int) $exitCode);
