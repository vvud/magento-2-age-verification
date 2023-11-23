/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 * This script is a Pop-up content on site
 */
define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/additional-validators',
    'Magentiz_AgeVerification/js/model/av-validator',
], function (Component, additionalValidators, avValidator) {
    'use strict';
    additionalValidators.registerValidator(avValidator);
    
    return Component.extend({});
});
