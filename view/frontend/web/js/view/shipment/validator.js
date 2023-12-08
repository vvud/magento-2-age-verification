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
        let saveIdCardUrl = window.checkoutConfig.saveIdCard;
        validator.addRule(
            'validate-av',
            function (value, params) {
                var isValid = true;
                if ($('#aveForm').length && !$('#age-verification-dob-valid').val()) {
                    $('#age-verification-dob').focus();
                    $('.av-dob-field').addClass('error');
                    $('.av-dob-error').text($.mage.__('This is a required field.'));
                    isValid = false;
                }

                // Validate if enable attachment
                if ($('.av-attachment-field').length) {
                    if (!$('input[name="attachment-id"]').length) {
                        $('#age-verification-attachment').focus();
                        $('.av-attachment-field').addClass('error');
                        isValid = false;
                    }
                }

                // Validate if enable id card
                if ($('.av-idcard-field').length) {
                    var errors = validateIdCard();
                    if (count(errors) > 0) {
                        for (var error in errors) {
                            $('#' + error).css('background', '#ffdfd4');
                            $('#' + error).css('color', 'red');
                            $('.' + error + '_error').show();
                        }
                        isValid = false;
                    } else {
                        let avType = $('input[name="verification_type"]:checked').val();
                        let avNumber = '';
                        $('.idcard-verification-item .idcardInput[required]').each(function() {
                            avNumber += $(this).val()+' ';
                        });
                        avNumber = $.trim(avNumber);
                        var formData = new FormData(), self = this;
                        formData.append('av_type', avType);
                        formData.append('av_number', avNumber);
                        if (window.FORM_KEY) {
                            formData.append('form_key', window.FORM_KEY);
                        }
                        $.ajax({
                            url: saveIdCardUrl,
                            type: 'POST',
                            data: formData,
                            success: function(res) {
                                if (res.success) {
                                    isValid = true;
                                } else {
                                    $('.av-idcard-error').show();
                                    isValid = false;
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                $('.av-idcard-error').show();
                                isValid = false;
                            },
                            cache: false,
                            contentType: false,
                            processData: false
                        });
                    }
                }

                return isValid;                     
            },
            $.mage.__('')
        );

        function validateIdCard() {
            const errors = [];
            var verificationType = $('input[name="verification_type"]:checked', '#aveForm').val();
            $('.idcardInput').css('background', '#fff');
            $('.idcardInput').css('color', 'inherit');
            $('.inputError').hide();
            if (verificationType == 'old') {
                var idcard_old_1_Value = $('#idcard_old_1').val();
                var idcard_old_1_Lenght = $('#idcard_old_1').val().length;
                var idcard_old_1_LenghtMax = $('#idcard_old_1').attr('maxlength');
                var idcard_old_2_Value = $('#idcard_old_2').val();
                var idcard_old_2_Lenght = $('#idcard_old_2').val().length;
                var idcard_old_2_LenghtMax = $('#idcard_old_2').attr('maxlength');
                var idcard_old_3_Value = $('#idcard_old_3').val();
                var idcard_old_3_Lenght = $('#idcard_old_3').val().length;
                var idcard_old_3_LenghtMax = $('#idcard_old_3').attr('maxlength');
                var idcard_old_4_Value = $('#idcard_old_4').val();
                var idcard_old_4_Lenght = $('#idcard_old_4').val().length;
                var idcard_old_4_LenghtMax = $('#idcard_old_4').attr('maxlength');
                if (idcard_old_1_Value == '' || idcard_old_1_Lenght < idcard_old_1_LenghtMax) {
                    errors['idcard_old_1'] = true;
                }
                if (idcard_old_2_Value == '' || idcard_old_2_Lenght < idcard_old_2_LenghtMax) {
                    errors['idcard_old_2'] = true;
                }
                if (idcard_old_3_Value == '' || idcard_old_3_Lenght < idcard_old_3_LenghtMax) {
                    errors['idcard_old_3'] = true;
                }
                if (idcard_old_4_Value == '' || idcard_old_4_Lenght < idcard_old_4_LenghtMax) {
                    errors['idcard_old_4'] = true;
                }
                if (!/^.*(d|D)$/.test(idcard_old_1_Value)) {
                    errors['idcard_old_1'] = 2;
                }
                if (!/\d\d\d\d\d\d\d/.test(idcard_old_2_Value)) {
                    errors['idcard_old_2'] = 2;
                }
                if (!/\d\d\d\d\d\d\d/.test(idcard_old_3_Value)) {
                    errors['idcard_old_3'] = 2;
                }
                if (!/\d/.test(idcard_old_4_Value)) {
                    errors['idcard_old_4'] = 2;
                }
            } else if (verificationType == 'new') {
                var idcard_new_1_Value = $('#idcard_new_1').val();
                var idcard_new_1_Lenght = $('#idcard_new_1').val().length;
                var idcard_new_1_LenghtMax = $('#idcard_new_1').attr('maxlength');
                var idcard_new_2_Value = $('#idcard_new_2').val();
                var idcard_new_2_Lenght = $('#idcard_new_2').val().length;
                var idcard_new_2_LenghtMax = $('#idcard_new_2').attr('maxlength');
                var idcard_new_3_Value = $('#idcard_new_3').val();
                var idcard_new_3_Lenght = $('#idcard_new_3').val().length;
                var idcard_new_3_LenghtMax = $('#idcard_new_3').attr('maxlength');
                var idcard_new_4_Value = $('#idcard_new_4').val();
                var idcard_new_4_Lenght = $('#idcard_new_4').val().length;
                var idcard_new_4_LenghtMax = $('#idcard_new_4').attr('maxlength');
                if (idcard_new_1_Value == '' || idcard_new_1_Lenght < idcard_new_1_LenghtMax) {
                    errors['idcard_new_1'] = true;
                }
                if (idcard_new_2_Value == '' || idcard_new_2_Lenght < idcard_new_2_LenghtMax) {
                    errors['idcard_new_2'] = true;
                }
                if (idcard_new_3_Value == '' || idcard_new_3_Lenght < idcard_new_3_LenghtMax) {
                    errors['idcard_new_3'] = true;
                }
                if (idcard_new_4_Value == '' || idcard_new_4_Lenght < idcard_new_4_LenghtMax) {
                    errors['idcard_new_4'] = true;
                }
                if (!/\d\d\d\d\d\d\d/.test(idcard_new_2_Value)) {
                    errors['idcard_new_2'] = 2;
                }
                if (!/^.*(d|D)$/.test(idcard_new_3_Value)) {
                    errors['idcard_new_3'] = 2;
                }
                if (!/\d/.test(idcard_new_4_Value)) {
                    errors['idcard_new_4'] = 2;
                }
            } else if (verificationType == 'aht') {
                var idcard_aht_1_Value = $('#idcard_aht_1').val();
                var idcard_aht_1_Lenght = $('#idcard_aht_1').val().length;
                var idcard_aht_1_LenghtMax = $('#idcard_aht_1').attr('maxlength');
                var idcard_aht_2_Value = $('#idcard_aht_2').val();
                var idcard_aht_2_Lenght = $('#idcard_aht_2').val().length;
                var idcard_aht_2_LenghtMax = $('#idcard_aht_2').attr('maxlength');
                var idcard_aht_3_Value = $('#idcard_aht_3').val();
                var idcard_aht_3_Lenght = $('#idcard_aht_3').val().length;
                var idcard_aht_3_LenghtMax = $('#idcard_aht_3').attr('maxlength');
                if (idcard_aht_1_Value == '' || idcard_aht_1_Lenght < idcard_aht_1_LenghtMax) {
                    errors['idcard_aht_1'] = true;
                }
                if (idcard_aht_2_Value == '' || idcard_aht_2_Lenght < idcard_aht_2_LenghtMax) {
                    errors['idcard_aht_2'] = true;
                }
                if (idcard_aht_3_Value == '' || idcard_aht_3_Lenght < idcard_aht_3_LenghtMax) {
                    errors['idcard_aht_3'] = true;
                }
                if (!/^.+[a-zA-Z]{3}$/.test(idcard_aht_2_Value)) {
                    errors['idcard_aht_2'] = true;
                }
                if (!/\d\d\d\d\d\d\d(F|f|M|m)\d\d\d\d\d\d\d[a-zA-Z]{3}/.test(idcard_aht_2_Value)) {
                    errors['idcard_aht_2'] = 2;
                }
                if (!/\d/.test(idcard_aht_3_Value)) {
                    errors['idcard_aht_3'] = 2;
                }
            } else if (verificationType == 'drp') {
                var idcard_drp_1_Value = $('#idcard_drp_1').val();
                var idcard_drp_1_Lenght = $('#idcard_drp_1').val().length;
                var idcard_drp_1_LenghtMax = $('#idcard_drp_1').attr('maxlength');
                var idcard_drp_2_Value = $('#idcard_drp_2').val();
                var idcard_drp_2_Lenght = $('#idcard_drp_2').val().length;
                var idcard_drp_2_LenghtMax = $('#idcard_drp_2').attr('maxlength');
                var idcard_drp_3_Value = $('#idcard_drp_3').val();
                var idcard_drp_3_Lenght = $('#idcard_drp_3').val().length;
                var idcard_drp_3_LenghtMax = $('#idcard_drp_3').attr('maxlength');
                if (idcard_drp_1_Value == '' || idcard_drp_1_Lenght < idcard_drp_1_LenghtMax) {
                    errors['idcard_drp_1'] = true;
                }
                if (idcard_drp_2_Value == '' || idcard_drp_2_Lenght < idcard_drp_2_LenghtMax) {
                    errors['idcard_drp_2'] = true;
                }
                if (idcard_drp_3_Value == '' || idcard_drp_3_Lenght < idcard_drp_3_LenghtMax) {
                    errors['idcard_drp_3'] = true;
                }
                if (!/^.*(d|D)$/.test(idcard_drp_1_Value)) {
                    errors['idcard_drp_1'] = 2;
                }
                if (!/\d\d\d\d\d\d\d(F|f|M|m)\d\d\d\d\d\d\d/.test(idcard_drp_2_Value)) {
                    errors['idcard_drp_2'] = 2;
                }
                if (!/\d/.test(idcard_drp_3_Value)) {
                    errors['idcard_drp_3'] = 2;
                }
            } else if (verificationType == 'aut') {
                var idcard_aut_1_Value = $('#idcard_aut_1').val();
                var idcard_aut_1_Lenght = $('#idcard_aut_1').val().length;
                var idcard_aut_1_LenghtMax = $('#idcard_aut_1').attr('maxlength');
                var idcard_aut_2_Value = $('#idcard_aut_2').val();
                var idcard_aut_2_Lenght = $('#idcard_aut_2').val().length;
                var idcard_aut_2_LenghtMax = $('#idcard_aut_2').attr('maxlength');
                var idcard_aut_3_Value = $('#idcard_aut_3').val();
                var idcard_aut_3_Lenght = $('#idcard_aut_3').val().length;
                var idcard_aut_3_LenghtMax = $('#idcard_aut_3').attr('maxlength');
                var idcard_aut_4_Value = $('#idcard_aut_4').val();
                var idcard_aut_4_Lenght = $('#idcard_aut_4').val().length;
                var idcard_aut_4_LenghtMax = $('#idcard_aut_4').attr('maxlength');
                if (idcard_aut_1_Value == '' || idcard_aut_1_Lenght < idcard_aut_1_LenghtMax) {
                    errors['idcard_aut_1'] = true;
                }
                if (idcard_aut_2_Value == '' || idcard_aut_2_Lenght < idcard_aut_2_LenghtMax) {
                    errors['idcard_aut_2'] = true;
                }
                if (idcard_aut_3_Value == '' || idcard_aut_3_Lenght < idcard_aut_3_LenghtMax) {
                    errors['idcard_aut_3'] = true;
                }
                if (idcard_aut_4_Value == '' || idcard_aut_4_Lenght < idcard_aut_4_LenghtMax) {
                    errors['idcard_aut_4'] = true;
                }
                if (!/\d/.test(idcard_aut_2_Value)) {
                    errors['idcard_aut_2'] = 1;
                }
                if (!/\d\d\d\d\d\d\d(F|f|M|m)\d\d\d\d\d\d\d/.test(idcard_aut_3_Value)) {
                    errors['idcard_aut_3'] = 2;
                }
                if (!/\d/.test(idcard_aut_4_Value)) {
                    errors['idcard_aut_4'] = 3;
                }
            } else if (verificationType == 'idc') {
                var idcard_idc_1_Value = $('#idcard_idc_1').val();
                var idcard_idc_1_Lenght = $('#idcard_idc_1').val().length;
                var idcard_idc_1_LenghtMax = $('#idcard_idc_1').attr('maxlength');
                var idcard_idc_2_Value = $('#idcard_idc_2').val();
                var idcard_idc_2_Lenght = $('#idcard_idc_2').val().length;
                var idcard_idc_2_LenghtMax = $('#idcard_idc_2').attr('maxlength');
                var idcard_idc_3_Value = $('#idcard_idc_3').val();
                var idcard_idc_3_Lenght = $('#idcard_idc_3').val().length;
                var idcard_idc_3_LenghtMax = $('#idcard_idc_3').attr('maxlength');
                if (idcard_idc_1_Value == '' || idcard_idc_1_Lenght < idcard_idc_1_LenghtMax) {
                    errors['idcard_idc_1'] = true;
                }
                if (idcard_idc_2_Value == '' || idcard_idc_2_Lenght < idcard_idc_2_LenghtMax) {
                    errors['idcard_idc_2'] = true;
                }
                if (idcard_idc_3_Value == '' || idcard_idc_3_Lenght < idcard_idc_3_LenghtMax) {
                    errors['idcard_idc_3'] = true;
                }
                if (!/[a-zA-Z]{3}/.test(idcard_idc_2_Value)) {
                    errors['idcard_idc_2'] = true;
                }
                if (!/\d\d\d\d\d\d\d(F|f|M|m)\d\d\d\d\d\d\d[a-zA-Z]{3}/.test(idcard_idc_2_Value)) {
                    errors['idcard_idc_2'] = 2;
                }
                if (!/\d/.test(idcard_idc_3_Value)) {
                    errors['idcard_idc_3'] = 2;
                }
            } else if (verificationType == 'pap') {
                var idcard_pap_1_Value = $('#idcard_pap_1').val();
                var idcard_pap_1_Lenght = $('#idcard_pap_1').val().length;
                var idcard_pap_1_LenghtMax = $('#idcard_pap_1').attr('maxlength');
                var idcard_pap_2_Value = $('#idcard_pap_2').val();
                var idcard_pap_2_Lenght = $('#idcard_pap_2').val().length;
                var idcard_pap_2_LenghtMax = $('#idcard_pap_2').attr('maxlength');
                var idcard_pap_3_Value = $('#idcard_pap_3').val();
                var idcard_pap_3_Lenght = $('#idcard_pap_3').val().length;
                var idcard_pap_3_LenghtMax = $('#idcard_pap_3').attr('maxlength');
                if (idcard_pap_1_Value == '' || idcard_pap_1_Lenght < idcard_pap_1_LenghtMax) {
                    errors['idcard_pap_1'] = true;
                }
                if (idcard_pap_2_Value == '' || idcard_pap_2_Lenght < idcard_pap_2_LenghtMax) {
                    errors['idcard_pap_2'] = true;
                }
                if (idcard_pap_3_Value == '' || idcard_pap_3_Lenght < idcard_pap_3_LenghtMax) {
                    errors['idcard_pap_3'] = true;
                }
                if (!/^.+[a-zA-Z]{3}$/.test(idcard_pap_1_Value)) {
                    errors['idcard_pap_1'] = true;
                }
                if (!/\d\d\d\d\d\d\d(F|f|M|m)\d\d\d\d\d\d\d/.test(idcard_pap_2_Value)) {
                    errors['idcard_pap_2'] = 2;
                }
                if (!/\d/.test(idcard_pap_3_Value)) {
                    errors['idcard_pap_3'] = 2;
                }
            } else if (verificationType == 'schufa') {
                if (!$('#schufa_terms').is(':checked')) {
                    errors['schufa'] = true;
                }
            } else {
                errors['idcard_empty'] = true;
            }

            return errors;
        };

        function count(array) {
            var c = 0;
            for (var i in array)
                if (array[i] != undefined)
                    c++;
            return c;
        };

        return validator;
    };
});
