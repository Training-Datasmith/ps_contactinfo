<?php

class PsContactinfoRenderWidgetTest extends PsContactinfoTestCase
{
    public function hookTemplateProvider()
    {
        return array(
            array('displayNav', array(), 'nav.tpl'),
            array('displayNav1', array(), 'nav.tpl'),
            array('displayNav12', array(), 'nav.tpl'),
            array('displayContactLeftColumn', array(), 'ps_contactinfo-rich.tpl'),
            array('displayContactRightColumn', array(), 'ps_contactinfo-rich.tpl'),
            array('displayLeftColumn', array(), 'ps_contactinfo-rich.tpl'),
            array('displayRightColumn', array(), 'ps_contactinfo-rich.tpl'),
            array('displayFooter', array(), 'ps_contactinfo.tpl'),
            array('displayContactContent', array(), 'ps_contactinfo.tpl'),
            array('displayHeader', array(), 'ps_contactinfo.tpl'),
            array('displayNavigation', array(), 'ps_contactinfo.tpl'),
            array('displaynav', array(), 'ps_contactinfo.tpl'),
            array('', array(), 'ps_contactinfo.tpl'),
            array(null, array(), 'ps_contactinfo.tpl'),
            array(array(), array(), 'ps_contactinfo.tpl'),
            array(null, array('hook' => 'displayNav1'), 'nav.tpl'),
            array('displayFooter', array('hook' => 'displayNav'), 'ps_contactinfo.tpl'),
        );
    }

    /**
     * @dataProvider hookTemplateProvider
     */
    public function testRenderWidgetSelectsTemplate($hookName, $configuration, $templateFile)
    {
        $expected = 'module:ps_contactinfo/' . $templateFile;
        $result = $this->module->renderWidget($hookName, $configuration);
        $this->assertSame('RENDERED:' . $expected, $result);
        $this->assertCount(1, Module::$fetchCalls);
        $this->assertSame($expected, Module::$fetchCalls[0]);
    }

    public function testAssignsWidgetVariables()
    {
        $this->module->renderWidget('displayFooter', array());
        $assign = $this->module->smarty->lastAssign;
        $this->assertSame('+33123456789', $assign['contact_infos']['phone']);
        $this->assertSame('0', $assign['display_email']);
    }

    public function testSameVariablesForNavAndFooter()
    {
        $nav = $this->module->getWidgetVariables('displayNav');
        $footer = $this->module->getWidgetVariables('displayFooter');
        $this->assertSame($nav, $footer);
        $this->assertSame('shop@example.test', $nav['contact_infos']['email']);
    }
}
