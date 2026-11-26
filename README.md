## Smile Retailer Offer Inventory 

This module is a plugin for [ElasticSuite](https://github.com/Smile-SA/elasticsuite).

This module add the ability to manage offers inventory per Retailer Shop.

### Compatibility

| Module version | Magento | Mage-OS | PHP |
| --- | --- | --- | --- |
| 1.3.x | 2.4.6 and later (tested up to 2.4.9) | 1.0.0 and later (tested up to 4.0) | 8.1 to 8.5, as supported by the platform |
| 1.2.x, from 1.2.1 | 2.4.4 and 2.4.5 | not available | 8.1 |

Releases up to 1.2.0 are not covered by this table: they declared no PHP requirement and any `magento/framework` version, and their method signatures use native `mixed` or union types, which the code generator of Magento 2.4.4 and 2.4.5 rejects (`setup:di:compile` fails).

### Requirements

The module requires :

- [Retailer offer](https://github.com/Smile-SA/magento2-module-retailer-offer) > 2.0.*

### How to use

1. Install the module via Composer :

``` composer require smile/module-retailer-offer-inventory ```

2. Enable it

``` bin/magento module:enable Smile_RetailerOfferInventory ```

3. Install the module and rebuild the DI cache

``` bin/magento setup:upgrade ```

### How to configure offers inventory

Go to magento backoffice

Menu : Sellers > Retailer Offers

Add a stock level for the current offer.
