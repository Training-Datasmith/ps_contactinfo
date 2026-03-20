<?php

declare(strict_types=1);

/**
 * Example: Working with the ps_contactinfo PrestaShop module.
 *
 * ps_contactinfo displays store contact information (address, phone, email,
 * fax) in the footer or sidebar. It reads from PrestaShop's Contact and
 * Shop configuration and renders via the widget system.
 *
 * This file documents common usage patterns.
 */

// --- Widget invocation in Smarty/Twig template ---
// {widget name="ps_contactinfo" hook="displayFooter"}

// --- Accessing store contact info programmatically ---
// PrestaShop stores contact details in Configuration:
//
// $phone    = Configuration::get('PS_SHOP_PHONE');
// $fax      = Configuration::get('PS_SHOP_FAX');
// $email    = Configuration::get('PS_SHOP_EMAIL');
// $address1 = Configuration::get('PS_SHOP_ADDR1');
// $address2 = Configuration::get('PS_SHOP_ADDR2');
// $city     = Configuration::get('PS_SHOP_CITY');
// $zip      = Configuration::get('PS_SHOP_CODE');
// $country  = new Country(Configuration::get('PS_SHOP_COUNTRY_ID'), $idLang);
//
// echo "$address1\n$city, $zip\n{$country->name}\nPhone: $phone\n";

// --- Back Office configuration ---
// Modules > Contact Information:
//   - Display phone number toggle
//   - Display fax number toggle
//   - Display email toggle
//   - Display address toggle
// (Underlying data edited at: Shop Parameters > Contact > Stores)

// --- Template override ---
// themes/{theme}/modules/ps_contactinfo/views/templates/hook/ps_contactinfo.tpl

// --- Hook placements ---
// Default: displayFooter (footer column)
// Additional: displayLeftColumn, displayRightColumn
