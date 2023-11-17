/**
 * This script is a Pop-up content on site
 */
 define(['jquery', 'Magento_Ui/js/modal/modal', 'mage/cookies'], function (
    $, Component
) {
    'use strict';
    // Get data from phtml file
    let mageJsComponent = function (data) {
        let getData = JSON.stringify(data);
        let jsongetData = JSON.parse(getData);
        let height_value = jsongetData['height'];
        let width_value = jsongetData['width'];
        let coockie_value = jsongetData['coockieexpire'];

        let options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            responsiveClass: 'ageverification-poup',
            clickableOverlay: false
        };

        $(document).keyup(function (e) {
            if (e.keyCode == 27) {
                return false;
            }
        });
        // Start : Pop-up age verification content
        $(document).ready(function () {
            $(document).mousedown(function (e) {
                return true;
            });
            // Set cookie condition below
            if ($.cookie('age_verify_status') != 1) {
                let ageconf = document.getElementsByClassName('ageconf');
                if (ageconf.length > 0) {
                    if (document.getElementById('popup').classList.contains('ageconf')) {
                        $(document).mousedown(function (e) {
                            return false;
                        });
                    }
                }

                // Set height and width of popup model
                if (width_value >= 500) {
                    $('.age-pop-up').css('width', width_value);
                } else if (width_value == 'auto') {
                    $('.age-pop-up').css('width', 'auto');
                } else {
                    $('.age-pop-up').css('width', '500px');
                }
                if (height_value >= 300) {
                    $('.age-pop-up').css('height', height_value);
                } else if (height_value == 'auto') {
                    $('.age-pop-up').css('height', 'auto');
                } else {
                    $('.age-pop-up').css('height', '300px');
                }

                $(document).on('change', '#agree_term_condition', function () {
                    if ($(this).is(':checked')) {
                        $('.agree_term_condition_error').slideUp();
                    }
                });

                // Show popup here
                jQuery('.age-pop-up').modal(options).modal('openModal');
                // Click on enter button
                $('#valid-age').click(function () {
                    if ($('#agree_term_condition:checked').length == 0) {
                        $('.agree_term_condition_error').slideDown();
                        return false;
                    }
                    if (coockie_value == '') {
                        coockie_value = 1;
                    }
                    $.cookie('age_verify_status', '1', {
                        expires: +coockie_value,
                    });
                    jQuery('.age-pop-up').modal('closeModal');
                });
            }
        });
        // End : Pop-up age verification content
    };

    return mageJsComponent;
});
