<?php

abstract class PsContactinfoTestCase extends PHPUnit_Framework_TestCase
{
    /** @var Ps_Contactinfo */
    protected $module;

    public static function setUpBeforeClass()
    {
        if (!class_exists('Ps_Contactinfo', false)) {
            require dirname(__DIR__) . '/ps_contactinfo.php';
            require dirname(__DIR__) . '/upgrade/upgrade-3.3.2.php';
            require dirname(__DIR__) . '/upgrade/upgrade-from-blockcontact.php';
        }
    }

    protected function setUp()
    {
        resetAllStubs();
        $this->module = new Ps_Contactinfo();
    }

    protected function tearDown()
    {
        resetAllStubs();
    }
}
