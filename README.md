## Smile Retailer Promotion 

This module is a plugin for [RetailerSuite](https://github.com/Smile-SA/elasticsuite-for-retailer).

This module add the ability to add promotion per Retailer Shop.

### Compatibility

| Module version | Magento | Mage-OS | PHP |
| --- | --- | --- | --- |
| 1.1.x | 2.4.6 and later (tested up to 2.4.9) | 1.0.0 and later (tested up to 4.0) | 8.1 to 8.5, as supported by the platform |
| 1.0.x, from 1.0.1 | 2.4.4 and 2.4.5 | not available | 8.1 |

Releases up to 1.0.0 are not covered by this table: they declared no PHP requirement and any `magento/framework` version, and their method signatures use native `mixed` or union types, which the code generator of Magento 2.4.4 and 2.4.5 rejects (`setup:di:compile` fails).

### Requirements

The module requires :

- [Retailer](https://github.com/Smile-SA/magento2-module-retailer) > 1.2.5
- [Seller](https://github.com/Smile-SA/magento2-module-seller) > 1.3.*

### How to use

1. Install the module via Composer :

``` composer require smile/module-retailer-promotion ```

2. Enable it

``` bin/magento module:enable Smile_RetailerPromotion ```

3. Install the module and rebuild the DI cache

``` bin/magento setup:upgrade ```

### How to manage promotions

Go to magento admin panel

Menu : Promotions > Promotions