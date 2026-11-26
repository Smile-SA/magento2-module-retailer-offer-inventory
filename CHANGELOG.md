# Changelog

All notable changes to this project will be documented in this file.

## [1.2.1] - Unreleased
[1.2.1]: https://github.com/Smile-SA/magento2-module-retailer-offer-inventory/compare/1.2.0...1.2.1

- Supported platforms: Magento 2.4.4 and 2.4.5 only, PHP 8.1
- `composer.json` now states it: `php ^8.1` and `magento/framework >=103.0.4 <103.0.6` (previously no PHP requirement and any `magento/framework` version)
- Magento 2.4.6 and later and Mage-OS are supported by the 1.3.x line
- Fix `setup:di:compile` on Magento 2.4.4 and 2.4.5: native `mixed` and union types are removed from method signatures and kept in docblocks
- Require the Smile modules of the same line: `smile/module-retailer-offer ~2.0.5`
- CI: static analysis on Magento 2.4.5, `setup:di:compile` on Magento 2.4.4 and 2.4.5

## [1.2.0] - 2024-03-12
[1.2.0]: https://github.com/Smile-SA/magento2-module-retailer-offer-inventory/compare/1.1.0...1.2.0

Magento 2.4.6 and PHP 8.2 compatibility
