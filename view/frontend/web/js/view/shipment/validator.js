/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 * This script is a Pop-up content on site
 */
 define([
    'jquery',
    'moment'
], function ($, moment) {
    'use strict';

    return function (validator) {
        validator.addRule(
            'validate-av',
            function (value, params) {
                var isValid = true;
                if (!$('#age-verification-dob-valid').val()) {
                    $('#age-verification-dob').focus();
                    $('.av-dob-field').addClass('error');
                    $('.av-dob-error').text($.mage.__('This is a required field.'));
                    isValid = false;
                }
                if (!$('input[name="attachment-id"]').length) {
                    $('#age-verification-attachment').focus();
                    $('.av-attachment-field').addClass('error');
                    isValid = false;
                }

                return isValid;                     
            },
            $.mage.__('')
        );

        return validator;
    };
});
