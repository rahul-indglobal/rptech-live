/**
 * Plumrocket Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End-user License Agreement
 * that is available through the world-wide-web at this URL:
 * http://wiki.plumrocket.net/wiki/EULA
 * If you are unable to obtain it through the world-wide-web, please
 * send an email to support@plumrocket.com so we can send you a copy immediately.
 *
 * @package     Plumrocket_SocialLoginFree
 * @copyright   Copyright (c) 2018 Plumrocket Inc. (http://www.plumrocket.com)
 * @license     http://wiki.plumrocket.net/wiki/EULA  End-user License Agreement
 */

require([
    'jquery',
    'domReady!'
], function($) {
    'use strict';

    var dobReadOnly = window.psloginDobReadOnly;
    var firstNameReadOnly = window.psloginFirstNameReadOnly;
    var lastNameReadOnly = window.psloginLastNameReadOnly;
    var genderReadOnly = window.psloginGenderReadOnly;
    var emailReadOnly = window.psloginEmailReadOnly;
    var generatedPassword = window.psloginGeneratedPassword;

    if (firstNameReadOnly === '0') {
        $('#firstname').prop('readonly', true);
    }

    if (lastNameReadOnly === '0') {
        $('#lastname').prop('readonly', true);
    }

    if (dobReadOnly === '0') {
        $('#dob').prop('disabled', true);
        $('#dob_hide').val($('#dob').val());
    }

    if (genderReadOnly === '0') {
        $('#gender option:not(:selected)').prop('disabled', true);
    }

    if (emailReadOnly === '0') {
        $('#email_address').prop('disabled', true);
    }

    $("#password").on('change', function () { setPassword(); });

    var setPassword = function() {
        if (typeof generatedPassword != 'undefined') {
            $('#password').val(generatedPassword);
            $('#password-confirmation').val(generatedPassword);
        }
    };

    if (typeof generatedPassword != 'undefined') {
        setPassword();
        $('div.field.password.required').hide();
        $('div.field.confirmation.required').hide();
    }
});
