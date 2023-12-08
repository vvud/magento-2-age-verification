/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */
define([
    'jquery',
    'ko',
    'uiComponent',
    'mage/translate',
    'mage/calendar'
], function ($, ko, Component) {
        'use strict';
        const default_dob = '0000-00-00';
        return Component.extend({
            defaults: {
                template: 'Magentiz_AgeVerification/payment/age-verification-markup'
            },
            attachmentList: ko.observableArray([]),
            isVisible: window.checkoutConfig.ageVerificationEnabled,
            
            initialize: function () {
                this._super();
                var self = this;
                var checkoutConfig = window.checkoutConfig;
                var quote = checkoutConfig.quoteData;
                this.ageVerificationTitle = checkoutConfig.ageVerificationTitle;
                this.additionalInfo = checkoutConfig.additionalInformation;  
                this.dob = quote.dob;
                this.verificationType = checkoutConfig.verificationType;
                this.allowedExtensions = checkoutConfig.attachmentExt;
                this.maxFileSize = checkoutConfig.attachmentSize;
                this.removeItem = checkoutConfig.removeItem;
                this.maxFileLimit = checkoutConfig.attachmentLimit;
                this.invalidExtError = checkoutConfig.attachmentInvalidExt;
                this.invalidSizeError = checkoutConfig.attachmentInvalidSize;
                this.invalidLimitError = checkoutConfig.attachmentInvalidLimit;
                this.saveDobUrl = checkoutConfig.saveDob;
                this.uploadUrl = checkoutConfig.attachmentUpload;
                this.removeUrl = checkoutConfig.attachmentRemove;
                this.attachments = checkoutConfig.attachments;
                this.attachmentList(this.attachments);
                this.files = checkoutConfig.totalCount;

                ko.bindingHandlers.datepicker = {
                    init: function (element, valueAccessor, allBindingsAccessor) {
                        var $el = $(element);
                        // Initialize datetimepicker
                        var options = {
                            yearRange: '-150:+0',
                            dateFormat: 'dd.mm.yy',
                            changeMonth: true,
                            changeYear: true,
                            beforeShow: function(input, inst) {
                                $(element).attr('placeholder', 'dd.mm.yyyy');
                            }
                        };

                        $el.datepicker(options);

                        var writable = valueAccessor();
                        if (!ko.isObservable(writable)) {
                            var propWriters = allBindingsAccessor()._ko_property_writers;
                            if (propWriters && propWriters.datepicker) {
                                writable = propWriters.datepicker;
                            } else {
                                return;
                            }
                        }
                        writable($(element).datepicker('getDate'));
                    },
                    update: function (element, valueAccessor) {
                        var widget = $(element).data('DatePicker');
                        // When the view model is updated, update the widget
                        if (widget) {
                            var date = ko.utils.unwrapObservable(valueAccessor());
                            widget.date(date);
                        }
                    }
                };

                return this;
            },

            /**
             * Show Loader
             */
            showRowLoader: function() {
                $('body').trigger('processStart');
            },

            /**
             * Hide Loader
             */
            hideRowLoader: function() {
               $('body').trigger('processStop');
            },

            /**
             * Get Av Title
             */
            getTitle: function() {
                return this.ageVerificationTitle;
            },

            /**
             * Get Av Additional data
             */
            getAdditionalInfo: function() {
                return this.additionalInfo;
            },

            /**
             * Get Dob data
             */
            getDob: function() {
                var dob = '';
                if (this.dob != default_dob) {
                    dob = this.dob;
                }
                return dob;
            },

            /**
             * Return value for check dob valid
             */
            checkDobValid: function() {
                var isValid = '';
                var dob = this.getDob();
                if (dob) {
                    isValid = '1';
                }
                return isValid;
            },

            /**
             * Trigger save method if dob is change
             */
            onDobChange: function () {
                this.saveDob();
            },

            /**
             * Save Dob handler
             */
            saveDob: function() {
                if ($('#age-verification-dob').val()) {
                    $('.av-dob-field').removeClass('error');
                    var result = true;
                    var formData = new FormData(), self = this;
                    formData.append('dob', $('#age-verification-dob').val());
                    if (window.FORM_KEY) {
                        formData.append('form_key', window.FORM_KEY);
                    }
                    $.ajax({
                        url: this.saveDobUrl,
                        type: 'POST',
                        data: formData,
                        success: function(res) {
                            if (res.success) {
                                $('#age-verification-dob-valid').val('1');
                                result = true;
                            } else {
                                $('#age-verification-dob-valid').val('');
                                $('.av-dob-error').text($.mage.__(res.error));
                                $('.av-dob-field').addClass('error');
                                result = false;
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            $('#age-verification-dob-valid').val('');
                            result = false;
                            self.addError(thrownError);
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
    
                    return result;
                }
            },

            /**
             * Check if validate use Id Card or not
             */
            allowIdCard: function() {
                if (this.verificationType == '0' || this.verificationType == '2') {
                    return true;
                }
                return false;
            },
            
            /**
             * Show verification type main content
             */
            onVerificationTypeChange: function(data, event) {
                var id = event.currentTarget.id;
                $('.idcard_empty_error').hide();
                $('.av-idcard-error').hide();
                $('.idcardInput, #schufa_terms').prop('required', false);
                $('.fraspyIdentificationSelectRight').hide();
                if (id == 'verification_mode_old') {
                    $('#idcard_old_1, #idcard_old_2, #idcard_old_3, #idcard_old_4').prop('required', true);
                    $('.idcard-verification-item').removeClass('active');
                } else if (id == 'verification_mode_new') {
                    $('#idcard_new_1, #idcard_new_2, #idcard_new_3, #idcard_new_4').prop('required', true);
                    $('.idcard-verification-item').removeClass('active');
                } else if (id == 'verification_mode_aht') {
                    $('#idcard_aht_1, #idcard_aht_2, #idcard_aht_3').prop('required', true);
                    $('.idcard-verification-item').removeClass('active');
                } else if (id == 'verification_mode_drp') {
                    $('#idcard_drp_1, #idcard_drp_2, #idcard_drp_3').prop('required', true);
                    $('.idcard-verification-item').removeClass('active');
                } else if (id == 'verification_mode_aut') {
                    $('#idcard_aut_1, #idcard_aut_2, #idcard_aut_3, #idcard_aut_4').prop('required', true);
                    $('.idcard-verification-item').removeClass('active');
                } else if (id == 'verification_mode_pap') {
                    $('#idcard_pap_1, #idcard_pap_2, #idcard_pap_3').prop('required', true);
                    $('.idcard-verification-item').removeClass('active');
                } else if (id == 'verification_mode_idc') {
                    $('#idcard_idc_1, #idcard_idc_2, #idcard_idc_3').prop('required', true);
                    $('.idcard-verification-item').removeClass('active');
                } else if (id == 'verification_mode_schufa') {
                    $('#schufa_terms').prop('required', true);
                    $('.idcard-verification-item').removeClass('active');
                }
                $('#' + id + '_container').addClass('active');
                return true;
            },

            /**
             * Check if validate use attachment upload or not
             */
            allowUpload: function() {
                if (this.verificationType == '0' || this.verificationType == '1') {
                    return true;
                }
                return false;
            },

            processingFile: function(file) {
                var error = this.validateFile(file);
                if (error) {
                    this.addError(error);
                } else {
                    if (this.files >= this.maxFileLimit) {
                        this.addError(this.invalidLimitError);
                    } else {
                        var uniq = Math.random().toString(32).slice(2);
                        this.upload(file, uniq);
                    }
                }
            },

            upload: function(file, pos) {
                var formAttach = new FormData(), self = this;

                this.showRowLoader();

                formAttach.append($('#age-verification-attachment').attr('name'), file);
                if (window.FORM_KEY) {
                    formAttach.append('form_key', window.FORM_KEY);
                }
                $.ajax({
                    url: this.uploadUrl,
                    type: 'POST',
                    data: formAttach,
                    success: function(data) {
                        var result = JSON.parse(data);
                        self.attachments.push(result);
                        self.attachmentList(self.attachments);
                        if(result['attachment_count']){
                            self.files = result['attachment_count'];
                        }
                        $('.av-attachment-field').removeClass('error');
                        self.hideRowLoader();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        self.addError(thrownError);
                        self.hideRowLoader();
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            },

            deleteFile: function(id, hash) {
                var attachParams = {
                    'attachment': id,
                    'hash': hash,
                    'form_key': window.FORM_KEY
                }, self = this;

                self.showRowLoader();

                $.ajax({
                    url: this.removeUrl,
                    type: 'POST',
                    data: $.param(attachParams),
                    success: function(data) {
                        var result = JSON.parse(data);
                        if (result.success) {
                            if (result['attachment_count']) {
                                self.files = result['attachment_count'];
                            }
                            $('#age-verification-attachment').val('');
                            $('div.sp-attachment-row[rel="' + hash + '"]').remove();
                        }
                        self.hideRowLoader();
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        self.addError(thrownError);
                        self.hideRowLoader();
                    }
                });
            },

            validateFile: function(file) {
                if (!this.checkFileExtension(file)) {
                    return this.invalidExtError;
                }
                if (!this.checkFileSize(file)) {
                    return this.invalidSizeError;
                }

                return null;
            },

            checkFileExtension: function(file) {
                var fileExt = file.name.split('.').pop().toLowerCase();
                var allowedExt = this.allowedExtensions.split(',');
                if (-1 == $.inArray(fileExt, allowedExt)) {
                    return false;
                }
                return true;
            },

            checkFileSize: function(file) {
                if ((file.size / 1024) > this.maxFileSize) {
                    return false;
                }
                return true;
            },

            addError: function(error) {
                var html = null;
                html = '<div class="sp-attachment-error danger"><strong class="close">X</strong>'+ error +'</div>';
                $('.attachment-container').before(html);
                $('.sp-attachment-error .close').on('click', function() {
                    var el = $(this).closest('div');
                    if (el.hasClass('sp-attachment-error')) {
                        $(el).slideUp('slow', function() {
                            $(this).remove();
                        });
                    }
                });
            },

            selectFiles: function() {
                $('#age-verification-attachment').trigger('click');
            },

            fileUpload: function(data, e) {
                var file    = e.target.files;
                for (var i = 0; i < file.length; i++) {
                    this.processingFile(file[i]);
                }
            },
            
            dragEnter: function(data, event) {},

            dragOver: function(data, event) {},

            drop: function(data, event) {
                $('.order-attachment-drag-area').css('border', '2px dashed #1979c3');
                var droppedFiles = event.originalEvent.dataTransfer.files;
                for (var i = 0; i < droppedFiles.length; i++) {
                    this.processingFile(droppedFiles[i]);
                }
            }
        });
    }
);
