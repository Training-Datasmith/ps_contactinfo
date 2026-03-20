# Architecture: ps_contactinfo

## Purpose

A PrestaShop front-office module that displays the store's contact information (address,
phone, email, opening hours) in a configurable widget position.

## Directory Structure

```
ps_contactinfo.php   # Main module class (WidgetInterface)
views/templates/      # Smarty/Twig templates
translations/         # Translation files
tests/                # PHPStan and unit tests
```

## Key Design Decisions

Reads contact information from PrestaShop's `Configuration` (store address, phone, email)
and the `Contact` model. Supports per-shop configuration in multi-shop setups. Implements
`WidgetInterface` for flexible placement.

## Extension Points

Override template in theme for custom styling and layout.
