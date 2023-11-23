/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */
var config = {
    waitSeconds: 0,
    deps: ['Magentiz_AgeVerification/js/age-verification-popup'],
    config: {
        mixins: {
            'Magento_Ui/js/lib/validation/validator': {
                'Magentiz_AgeVerification/js/view/shipment/validator': true
            }
        }
    }
};
