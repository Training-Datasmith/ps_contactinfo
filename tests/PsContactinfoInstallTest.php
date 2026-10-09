<?php

class PsContactinfoInstallTest extends PsContactinfoTestCase
{
    public function testInstallRegistersHooksAndEnablesEmail()
    {
        $this->assertTrue($this->module->install());
        $this->assertCount(1, Configuration::$updateCalls);
        $this->assertSame('PS_CONTACT_INFO_DISPLAY_EMAIL', Configuration::$updateCalls[0][0]);
        $this->assertSame(1, Configuration::$updateCalls[0][1]);
        $this->assertCount(1, Module::$registerHookCalls);
        $this->assertSame(
            array(
                'displayNav',
                'displayNav1',
                'displayFooter',
                'displayContactRightColumn',
                'displayContactLeftColumn',
                'displayContactContent',
                'actionAdminStoresControllerUpdate_optionsAfter',
            ),
            Module::$registerHookCalls[0]
        );
    }

    public function testInstallStopsWhenParentFails()
    {
        Module::$parentInstallResult = false;
        $this->assertFalse($this->module->install());
        $this->assertCount(0, Configuration::$updateCalls);
        $this->assertCount(0, Module::$registerHookCalls);
    }

    public function testInstallStopsWhenConfigUpdateFails()
    {
        Configuration::$updateValueResult = false;
        $this->assertFalse($this->module->install());
        $this->assertCount(0, Module::$registerHookCalls);
    }

    public function testInstallReturnsFalseWhenHookRegistrationFails()
    {
        Module::$registerHookResult = false;
        $this->assertFalse($this->module->install());
    }
}
