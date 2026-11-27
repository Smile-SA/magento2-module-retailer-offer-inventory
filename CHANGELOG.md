# Changelog

All notable changes to this project will be documented in this file.

## [1.3.0] - Unreleased
[1.3.0]: https://github.com/Smile-SA/magento2-module-retailer-offer-inventory/compare/1.2.0...1.3.0

- Supported platforms: Magento 2.4.6 and later, Mage-OS 1.0.0 and later (tested up to Magento 2.4.9 and Mage-OS 4.0), PHP 8.1 to 8.5
- `composer.json` now states it: `php ^8.1` and `magento/framework >=103.0.6` (previously no PHP requirement and any `magento/framework` version)
- Magento 2.4.4 and 2.4.5 are supported by the 1.2.x line, from 1.2.1
- CI: static analysis and `setup:di:compile` run on Mage-OS, without Magento credentials
- Fix the fatal error on `GET /V1/retailerofferinventory/:itemId` (`getItemId()` returned a string)
- Fix the offer inventory not given back when an order is canceled with Magento Inventory (MSI) enabled

## [1.2.0] - 2024-03-12
[1.2.0]: https://github.com/Smile-SA/magento2-module-retailer-offer-inventory/compare/1.1.0...1.2.0

Magento 2.4.6 and PHP 8.2 compatibility
