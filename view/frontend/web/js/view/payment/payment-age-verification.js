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
        return Component.extend({
            defaults: {
                template: 'Magentiz_AgeVerification/payment/age-verification-markup'
            },
            attachmentList: ko.observableArray([]),
            isVisible: window.checkoutConfig.ageVerificationEnabled,
            
            initialize: function () {
                this._super();
                var self = this;
                var quote = window.checkoutConfig.quoteData;
                this.ageVerificationTitle = window.checkoutConfig.ageVerificationTitle;
                this.additionalInfo = window.checkoutConfig.additionalInformation;  
                this.dob = quote.dob;
                this.allowedExtensions = window.checkoutConfig.attachmentExt;
                this.maxFileSize = window.checkoutConfig.attachmentSize;
                this.removeItem = window.checkoutConfig.removeItem;
                this.maxFileLimit = window.checkoutConfig.attachmentLimit;
                this.invalidExtError = window.checkoutConfig.attachmentInvalidExt;
                this.invalidSizeError = window.checkoutConfig.attachmentInvalidSize;
                this.invalidLimitError = window.checkoutConfig.attachmentInvalidLimit;
                this.bodUpdateUrl = window.checkoutConfig.bodUpdate;
                this.uploadUrl = window.checkoutConfig.attachmentUpload;
                this.removeUrl = window.checkoutConfig.attachmentRemove;
                this.attachments = window.checkoutConfig.attachments;              
                this.attachmentList(this.attachments);
                this.files = window.checkoutConfig.totalCount;

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
                return this.dob;
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
                    var result = true;
                    var formData = new FormData(), self = this;
                    formData.append('dob', $('#age-verification-dob').val());
                    if (window.FORM_KEY) {
                        formData.append('form_key', window.FORM_KEY);
                    }
                    $.ajax({
                        url: this.bodUpdateUrl,
                        type: 'POST',
                        data: formData,
                        success: function(res) {
                            if (res.success) {
                                $('.dob-error').slideUp();
                                $('#age-verification-dob-valid').val('1');
                                result = true;
                            } else {
                                $('#age-verification-dob-valid').val('');
                                $('.dob-error').text($.mage.__(res.error)).slideDown();
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

                formAttach.append($('#age-verification-attachment').attr("name"), file);
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
