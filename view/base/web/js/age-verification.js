/**
 * Copyright © Open Techiz. All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 */
define([
    'jquery',
    'ko',
    'uiComponent'
], function ($, ko, Component) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Magentiz_AgeVerification/age-verification-markup'
            },
            attachmentList: ko.observableArray([]),

            initialize: function () {
                this._super();
                var self = this;
                this.attachments = this.dataconfig.attachments;
                this.dob = this.dataconfig.dob;

                this.attachmentList(this.attachments);
                this.files = this.dataconfig.totalCount;
            },

            showRowLoader: function() {
                $('body').trigger('processStart');
            },

            hideRowLoader: function() {
               $('body').trigger('processStop');
            },

            getDob: function() {
                return this.dob;
            },

            downloadFile: function () {
                window.location = this.download;
            }
        });
    }
);
