<?php

class PsContactinfoCacheHookTest extends PsContactinfoTestCase
{
    public function testStoreOptionsHookClearsEveryTemplate()
    {
        $this->assertTrue($this->module->hookActionAdminStoresControllerUpdate_optionsAfter());
        sort(Module::$clearCacheCalls);
        $this->assertEquals(
            array('nav.tpl', 'ps_contactinfo-rich.tpl', 'ps_contactinfo.tpl'),
            Module::$clearCacheCalls
        );
    }
}
