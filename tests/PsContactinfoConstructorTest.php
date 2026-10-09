<?php

class PsContactinfoConstructorTest extends PsContactinfoTestCase
{
    public function testIdentity()
    {
        $this->assertSame('ps_contactinfo', $this->module->name);
        $this->assertSame('PrestaShop', $this->module->author);
        $this->assertSame('3.3.3', $this->module->version);
        $this->assertTrue($this->module->bootstrap);
    }

    public function testCompliancyUsesRuntimeVersion()
    {
        $this->assertSame('1.7.2.0', $this->module->ps_versions_compliancy['min']);
        $this->assertSame('9.9.9-test', $this->module->ps_versions_compliancy['max']);
    }

    public function testTranslatedLabels()
    {
        $this->assertSame('Contact information', $this->module->displayName);
        $this->assertSame(
            'Let your customers know how to reach you, display contact information on your store.',
            $this->module->description
        );
        $domains = array();
        foreach (Module::$transCalls as $call) {
            $domains[] = $call['domain'];
        }
        $this->assertContains('Modules.Contactinfo.Admin', $domains);
    }

    public function testParentConstructorRuns()
    {
        $this->assertTrue(Module::$parentConstructorCalled);
    }

    public function testImplementsWidgetInterface()
    {
        $implements = class_implements($this->module);
        $this->assertContains('PrestaShop\\PrestaShop\\Core\\Module\\WidgetInterface', $implements);

        $reflection = new ReflectionMethod('Ps_Contactinfo', 'renderWidget');
        $parameters = $reflection->getParameters();
        $this->assertCount(2, $parameters);
        $this->assertTrue($parameters[0]->isOptional());
        $this->assertTrue($parameters[1]->isArray());

        $reflection = new ReflectionMethod('Ps_Contactinfo', 'getWidgetVariables');
        $parameters = $reflection->getParameters();
        $this->assertCount(2, $parameters);
        $this->assertTrue($parameters[0]->isOptional());
        $this->assertTrue($parameters[1]->isArray());
    }

    public function testConfigXmlVersionMatchesClass()
    {
        $xml = simplexml_load_file(dirname(__DIR__) . '/config.xml');
        $this->assertSame('3.3.3', (string) $xml->version);
        $this->assertSame('3.3.3', $this->module->version);
        $this->assertSame('front_office_features', (string) $xml->tab);
        $this->assertSame('front_office_features', $this->module->tab);
    }
}
