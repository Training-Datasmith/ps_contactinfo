<?php

class PsContactinfoLoadGuardTest extends PsContactinfoTestCase
{
    public function testModuleFileExitsWithoutVersionConstant()
    {
        $without = $this->runPhpProcess($this->buildModuleProbeScript(false));
        $this->assertNotContains('DEFINED', $without['stdout']);
        $this->assertSame(0, $without['exitCode']);

        $with = $this->runPhpProcess($this->buildModuleProbeScript(true));
        $this->assertContains('DEFINED', $with['stdout']);
        $this->assertSame(0, $with['exitCode']);
    }

    public function testUpgrade332FileExitsWithoutVersionConstant()
    {
        $without = $this->runPhpProcess($this->buildUpgrade332ProbeScript(false));
        $this->assertNotContains('DEFINED', $without['stdout']);
        $this->assertSame(0, $without['exitCode']);

        $with = $this->runPhpProcess($this->buildUpgrade332ProbeScript(true));
        $this->assertContains('DEFINED', $with['stdout']);
        $this->assertSame(0, $with['exitCode']);
    }

    public function testUpgrade20FileExitsWithoutVersionConstant()
    {
        $without = $this->runPhpProcess($this->buildUpgrade20ProbeScript(false));
        $this->assertNotContains('DEFINED', $without['stdout']);
        $this->assertSame(0, $without['exitCode']);

        $with = $this->runPhpProcess($this->buildUpgrade20ProbeScript(true));
        $this->assertContains('DEFINED', $with['stdout']);
        $this->assertSame(0, $with['exitCode']);
    }

    private function runPhpProcess($script)
    {
        $descriptor = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('pipe', 'w'),
        );
        $process = proc_open('php', $descriptor, $pipes, dirname(__DIR__));
        fwrite($pipes[0], $script);
        fclose($pipes[0]);
        $stdout = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        return array(
            'stdout' => $stdout,
            'stderr' => $stderr,
            'exitCode' => $exitCode,
        );
    }

    private function buildModuleProbeScript($withVersion)
    {
        $root = dirname(__DIR__);
        $versionLine = $withVersion ? "define('_PS_VERSION_', 'probe');\n" : '';

        $interfaceFile = __DIR__ . '/WidgetInterface.php';

        return <<<PHP
<?php
class Module {}
require '{$interfaceFile}';
{$versionLine}require '{$root}/ps_contactinfo.php';
if (class_exists('Ps_Contactinfo', false)) {
    echo 'DEFINED';
}
PHP;
    }

    private function buildUpgrade332ProbeScript($withVersion)
    {
        $root = dirname(__DIR__);
        $versionLine = $withVersion ? "define('_PS_VERSION_', 'probe');\n" : '';

        return <<<PHP
<?php
{$versionLine}require '{$root}/upgrade/upgrade-3.3.2.php';
if (function_exists('upgrade_module_3_3_2')) {
    echo 'DEFINED';
}
PHP;
    }

    private function buildUpgrade20ProbeScript($withVersion)
    {
        $root = dirname(__DIR__);
        $versionLine = $withVersion ? "define('_PS_VERSION_', 'probe');\n" : '';

        return <<<PHP
<?php
class Configuration {
    public static function deleteByName(\$key) { return true; }
}
{$versionLine}require '{$root}/upgrade/upgrade-from-blockcontact.php';
if (function_exists('upgrade_module_2_0')) {
    echo 'DEFINED';
}
PHP;
    }
}
