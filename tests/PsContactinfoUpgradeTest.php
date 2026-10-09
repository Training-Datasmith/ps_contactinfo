<?php

class PsContactinfoUpgradeTest extends PsContactinfoTestCase
{
    public function testUpgrade332RegistersContactColumns()
    {
        $this->assertTrue(upgrade_module_3_3_2($this->module));
        $this->assertCount(1, Module::$registerHookCalls);
        $this->assertSame(
            array('displayContactRightColumn', 'displayContactLeftColumn'),
            Module::$registerHookCalls[0]
        );
    }

    public function testUpgrade332ReturnsFalse()
    {
        Module::$registerHookResult = false;
        $this->assertFalse(upgrade_module_3_3_2($this->module));
    }

    public function testUpgrade20DeletesLegacyKeys()
    {
        $this->assertTrue(upgrade_module_2_0($this->module));
        $this->assertSame(
            array('BLOCKCONTACT_TELNUMBER', 'BLOCKCONTACT_EMAIL'),
            Configuration::$deleteCalls
        );
    }

    public function testUpgrade20ShortCircuitOnFirstFailure()
    {
        Configuration::$deleteByNameResults = array(false);
        $this->assertFalse(upgrade_module_2_0($this->module));
        $this->assertCount(1, Configuration::$deleteCalls);
    }

    public function testUpgrade20ShortCircuitOnSecondFailure()
    {
        Configuration::$deleteByNameResults = array(true, false);
        $this->assertFalse(upgrade_module_2_0($this->module));
        $this->assertCount(2, Configuration::$deleteCalls);
    }
}
