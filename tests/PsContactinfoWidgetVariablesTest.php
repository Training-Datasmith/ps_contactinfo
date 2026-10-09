<?php

class PsContactinfoWidgetVariablesTest extends PsContactinfoTestCase
{
    public function testMapsAddressAndConfiguration()
    {
        $variables = $this->module->getWidgetVariables('displayFooter', array());

        $this->assertSame('Demo Shop', $variables['contact_infos']['company']);
        $this->assertSame('10 Rue de Rivoli', $variables['contact_infos']['address']['address1']);
        $this->assertSame('Batiment B', $variables['contact_infos']['address']['address2']);
        $this->assertSame('75001', $variables['contact_infos']['address']['postcode']);
        $this->assertSame('Paris', $variables['contact_infos']['address']['city']);
        $this->assertSame('+33123456789', $variables['contact_infos']['phone']);
        $this->assertSame('+33123456780', $variables['contact_infos']['fax']);
        $this->assertSame('shop@example.test', $variables['contact_infos']['email']);
        $this->assertSame("Open 9-5\nClosed Sunday", $variables['contact_infos']['details']);
        $this->assertSame('Frankreich', $variables['contact_infos']['address']['country']);
        $this->assertSame('0', $variables['display_email']);
        $this->assertSame('10 Rue de Rivoli<br />75001 Paris', $variables['contact_infos']['address']['formatted']);
        $this->assertSame(array(), AddressFormat::$lastCall['rules']);
        $this->assertSame('<br />', AddressFormat::$lastCall['separator']);
        $this->assertInstanceOf('AddressStub', AddressFormat::$lastCall['address']);
    }

    public function testStateNameIsTheFullString()
    {
        $this->module->context->language->id = 1;
        $variables = $this->module->getWidgetVariables('displayFooter', array());
        $this->assertSame('California', $variables['contact_infos']['address']['state']);
    }

    public function missingStateProvider()
    {
        return array(
            array(null),
            array(0),
            array('0'),
            array(''),
        );
    }

    /**
     * @dataProvider missingStateProvider
     */
    public function testMissingStateIsNullAndDoesNotLoadState($idState)
    {
        $address = AddressStub::getDefault();
        $address->id_state = $idState;
        $this->module->context->shop = new ShopWithAddressStub($address);

        $variables = $this->module->getWidgetVariables('displayFooter', array());
        $this->assertNull($variables['contact_infos']['address']['state']);
        $this->assertSame(0, State::$constructorCount);
    }

    public function testUncleanFormattedAddressBecomesEmpty()
    {
        AddressFormat::$returnValue = '<script>alert(1)</script>';
        Validate::$isCleanHtmlResult = false;

        $variables = $this->module->getWidgetVariables('displayFooter', array());
        $this->assertSame('', $variables['contact_infos']['address']['formatted']);
        $this->assertSame('10 Rue de Rivoli', $variables['contact_infos']['address']['address1']);
    }

    public function testUnsetConfigurationIsPassedThrough()
    {
        unset(Configuration::$store['PS_SHOP_PHONE']);
        unset(Configuration::$store['PS_CONTACT_INFO_DISPLAY_EMAIL']);

        $variables = $this->module->getWidgetVariables('displayFooter', array());
        $this->assertFalse($variables['contact_infos']['phone']);
        $this->assertFalse($variables['display_email']);
    }
}

class ShopWithAddressStub
{
    private $address;

    public function __construct($address)
    {
        $this->address = $address;
    }

    public function getAddress()
    {
        return $this->address;
    }
}
