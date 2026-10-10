<?php

class PsContactinfoGetContentTest extends PsContactinfoTestCase
{
    public function testFormWhenNotSubmitted()
    {
        Configuration::$store['PS_CONTACT_INFO_DISPLAY_EMAIL'] = '1';
        $result = $this->module->getContent();
        $this->assertSame('1', HelperForm::$lastFieldsValue['PS_CONTACT_INFO_DISPLAY_EMAIL']);
        $this->assertSame('FORMHTML', $result);
        $this->assertSame('submitContactInfo', HelperForm::$lastSubmitAction);
        $this->assertSame('PS_CONTACT_INFO_DISPLAY_EMAIL', HelperForm::$lastGenerateFormArgument[0]['form']['input'][0]['name']);
        $this->assertSame('switch', HelperForm::$lastGenerateFormArgument[0]['form']['input'][0]['type']);
        $values = HelperForm::$lastGenerateFormArgument[0]['form']['input'][0]['values'];
        $this->assertSame(1, $values[0]['value']);
        $this->assertSame(0, $values[1]['value']);
        $this->assertCount(0, Configuration::$updateCalls);
        $this->assertNull(Tools::$redirectUrl);
    }

    public function testSubmitStoresIntegerAndRedirectsWithZero()
    {
        Tools::$isSubmit = true;
        Tools::$getValue = '0';

        try {
            $this->module->getContent();
            $this->fail('Expected redirect exception');
        } catch (RedirectAdminException $exception) {
            $this->assertSame('PS_CONTACT_INFO_DISPLAY_EMAIL', Configuration::$updateCalls[0][0]);
            $this->assertSame(0, Configuration::$updateCalls[0][1]);
        }

        $this->assertSame(
            'https://shop.test/admin/index.php?controller=AdminModules&token=test-token&conf=6&configure=ps_contactinfo&tab_module=front_office_features&module_name=ps_contactinfo',
            Tools::$redirectUrl
        );
        $this->assertSame(
            array('nav.tpl', 'ps_contactinfo-rich.tpl', 'ps_contactinfo.tpl'),
            Module::$clearCacheCalls
        );
    }

    public function testSubmitStoresIntegerOne()
    {
        Tools::$isSubmit = true;
        Tools::$getValue = '1';

        try {
            $this->module->getContent();
            $this->fail('Expected redirect exception');
        } catch (RedirectAdminException $exception) {
            $this->assertSame(1, Configuration::$updateCalls[0][1]);
        }
    }

    public function testSubmitCastsDirtyValueToInteger()
    {
        Tools::$isSubmit = true;
        Tools::$getValue = '2foo';

        try {
            $this->module->getContent();
            $this->fail('Expected redirect exception');
        } catch (RedirectAdminException $exception) {
            $this->assertInternalType('int', Configuration::$updateCalls[0][1]);
        }
    }
}
