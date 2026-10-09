<?php

if (!defined('_PS_VERSION_')) {
    define('_PS_VERSION_', '9.9.9-test');
}

require __DIR__ . '/WidgetInterface.php';
require __DIR__ . '/PsContactinfoTestCase.php';

class Module
{
    public $name;
    public $author;
    public $version;
    public $bootstrap;
    public $displayName;
    public $description;
    public $ps_versions_compliancy;
    public $tab = 'front_office_features';
    public $context;
    public $smarty;

    public static $parentInstallResult = true;
    public static $parentConstructorCalled = false;
    public static $registerHookResult = true;
    public static $registerHookCalls = array();
    public static $fetchCalls = array();
    public static $clearCacheCalls = array();
    public static $transCalls = array();

    public function __construct()
    {
        self::$parentConstructorCalled = true;
        $this->context = new Context();
        $this->smarty = new SmartyStub();
    }

    public function install()
    {
        return self::$parentInstallResult;
    }

    public function registerHook($hooks)
    {
        self::$registerHookCalls[] = $hooks;

        return self::$registerHookResult;
    }

    public function trans($source, $parameters, $domain)
    {
        self::$transCalls[] = array(
            'source' => $source,
            'domain' => $domain,
        );

        return $source;
    }

    public function fetch($template)
    {
        self::$fetchCalls[] = $template;

        return 'RENDERED:' . $template;
    }

    public function _clearCache($template)
    {
        self::$clearCacheCalls[] = $template;
    }

    public function displayConfirmation($message)
    {
        return 'CONFIRMATION:' . $message;
    }

    public static function resetStubState()
    {
        self::$parentInstallResult = true;
        self::$parentConstructorCalled = false;
        self::$registerHookResult = true;
        self::$registerHookCalls = array();
        self::$fetchCalls = array();
        self::$clearCacheCalls = array();
        self::$transCalls = array();
    }
}

class Context
{
    public $shop;
    public $language;
    public $link;

    public function __construct()
    {
        $this->shop = new ShopStub();
        $this->language = new LanguageStub();
        $this->link = new LinkStub();
    }
}

class ShopStub
{
    public function getAddress()
    {
        return AddressStub::getDefault();
    }
}

class LanguageStub
{
    public $id = 2;
}

class LinkStub
{
    public function getAdminLink($controller, $withToken)
    {
        return 'https://shop.test/admin/index.php?controller=' . $controller . '&token=test-token';
    }
}

class SmartyStub
{
    public $lastAssign;

    public function assign($variables)
    {
        $this->lastAssign = $variables;
    }
}

class AddressStub
{
    public $address1 = '10 Rue de Rivoli';
    public $address2 = 'Batiment B';
    public $postcode = '75001';
    public $city = 'Paris';
    public $id_state = 42;
    public $id_country = 8;

    public static function getDefault()
    {
        return new self();
    }
}

class Configuration
{
    public static $store = array(
        'PS_SHOP_NAME' => 'Demo Shop',
        'PS_SHOP_PHONE' => '+33123456789',
        'PS_SHOP_FAX' => '+33123456780',
        'PS_SHOP_EMAIL' => 'shop@example.test',
        'PS_SHOP_DETAILS' => "Open 9-5\nClosed Sunday",
        'PS_CONTACT_INFO_DISPLAY_EMAIL' => '0',
    );
    public static $updateCalls = array();
    public static $deleteCalls = array();
    public static $updateValueResult = true;
    public static $deleteByNameResults = array();

    public static function get($key)
    {
        if (!array_key_exists($key, self::$store)) {
            return false;
        }

        return self::$store[$key];
    }

    public static function updateValue($key, $value)
    {
        self::$updateCalls[] = array($key, $value);
        if (self::$updateValueResult) {
            self::$store[$key] = $value;
        }

        return self::$updateValueResult;
    }

    public static function deleteByName($key)
    {
        self::$deleteCalls[] = $key;
        if (self::$deleteByNameResults !== array()) {
            $result = array_shift(self::$deleteByNameResults);
            if ($result) {
                unset(self::$store[$key]);
            }

            return $result;
        }
        unset(self::$store[$key]);

        return true;
    }

    public static function resetStubState()
    {
        self::$store = array(
            'PS_SHOP_NAME' => 'Demo Shop',
            'PS_SHOP_PHONE' => '+33123456789',
            'PS_SHOP_FAX' => '+33123456780',
            'PS_SHOP_EMAIL' => 'shop@example.test',
            'PS_SHOP_DETAILS' => "Open 9-5\nClosed Sunday",
            'PS_CONTACT_INFO_DISPLAY_EMAIL' => '0',
        );
        self::$updateCalls = array();
        self::$deleteCalls = array();
        self::$updateValueResult = true;
        self::$deleteByNameResults = array();
    }
}

class Tools
{
    public static $isSubmit = false;
    public static $getValue = null;
    public static $redirectUrl = null;

    public static function isSubmit($name)
    {
        return self::$isSubmit && $name === 'submitContactInfo';
    }

    public static function getValue($name)
    {
        return self::$getValue;
    }

    public static function redirectAdmin($url)
    {
        self::$redirectUrl = $url;
        throw new RedirectAdminException($url);
    }

    public static function resetStubState()
    {
        self::$isSubmit = false;
        self::$getValue = null;
        self::$redirectUrl = null;
    }
}

class RedirectAdminException extends Exception
{
}

class HelperForm
{
    public $submit_action;
    public $fields_value = array();
    public static $lastGenerateFormArgument;
    public static $lastFieldsValue = array();

    public function generateForm($form)
    {
        self::$lastGenerateFormArgument = $form;
        self::$lastFieldsValue = $this->fields_value;

        return 'FORMHTML';
    }

    public static function resetStubState()
    {
        self::$lastGenerateFormArgument = null;
        self::$lastFieldsValue = array();
    }
}

class AddressFormat
{
    public static $returnValue = '10 Rue de Rivoli<br />75001 Paris';
    public static $lastCall;

    public static function generateAddress($address, $rules, $separator)
    {
        self::$lastCall = array(
            'address' => $address,
            'rules' => $rules,
            'separator' => $separator,
        );

        return self::$returnValue;
    }

    public static function resetStubState()
    {
        self::$returnValue = '10 Rue de Rivoli<br />75001 Paris';
        self::$lastCall = null;
    }
}

class Validate
{
    public static $isCleanHtmlResult = true;

    public static function isCleanHtml($value)
    {
        return self::$isCleanHtmlResult;
    }

    public static function resetStubState()
    {
        self::$isCleanHtmlResult = true;
    }
}

class State
{
    public $name;
    public static $constructorCount = 0;
    public static $namesById = array(
        42 => 'California',
    );

    public function __construct($id)
    {
        ++self::$constructorCount;
        $this->name = array_key_exists($id, self::$namesById) ? self::$namesById[$id] : '';
    }

    public static function resetStubState()
    {
        self::$constructorCount = 0;
        self::$namesById = array(
            42 => 'California',
        );
    }
}

class Country
{
    public $name;

    public function __construct($id)
    {
        $this->name = array(
            1 => 'France',
            2 => 'Frankreich',
        );
    }
}

function resetAllStubs()
{
    Module::resetStubState();
    Configuration::resetStubState();
    Tools::resetStubState();
    HelperForm::resetStubState();
    AddressFormat::resetStubState();
    Validate::resetStubState();
    State::resetStubState();
}
