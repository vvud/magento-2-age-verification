/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 * This script is a Pop-up content on site
 */
define([
    'jquery',
    'Magento_Ui/js/model/messageList'
], function ($, messageList) {
    'use strict';
    return {
        validate: function () {
            var isValid = true;
            if (!$('#age-verification-dob-valid').val() || !$('input[name="attachment-id"]').length) {
                isValid = false;
            }
            if (!isValid) {
                messageList.addErrorMessage({
                    message: ($.mage.__('Please check Age Verification information again!')),
                });
            }

            return isValid;
        },
    };
});
