## Smile Retailer Offer Inventory 

This module is a plugin for [ElasticSuite](https://github.com/Smile-SA/elasticsuite).

This module add the ability to manage offers inventory per Retailer Shop.

### Requirements

The module requires :

- [Retailer offer](https://github.com/Smile-SA/magento2-module-retailer-offer) > 1.3.*

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
