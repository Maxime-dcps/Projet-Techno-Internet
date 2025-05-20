$(document).ready(function() {

    var usernameTimeout;
    var emailTimeout;
    var debounceTime = 500;

    let isUsernameAjaxValid = false;
    let isEmailAjaxValid = false;

    function checkFormValidityAndToggleButton() {

        if (isUsernameAjaxValid && isEmailAjaxValid) {
            $('#register-btn').prop('disabled', false);
            $('#register-btn-div').removeClass('button-wrapper-disabled');
        } else {
            $('#register-btn').prop('disabled', true);
            $('#register-btn-div').addClass('button-wrapper-disabled');
        }
    }

    // Désactiver le bouton initialement
    checkFormValidityAndToggleButton();

    function setFeedback(fieldId, message, isValid) {
        const inputEl = $('#' + fieldId);
        const feedbackEl = $('#' + fieldId + '-feedback');

        inputEl.removeClass('is-invalid is-valid');
        feedbackEl.text('').hide();

        if (message) {
            if (!isValid) {
                inputEl.addClass('is-invalid');
                feedbackEl.text(message).show();
            }
        }
        else checkFormValidityAndToggleButton();
    }

    $('#username').on('input keyup', function() {-
        clearTimeout(usernameTimeout);
        const username = $(this).val().trim();
        const fieldId = 'username';

        if (username.length === 0) {
            isUsernameAjaxValid = false
            setFeedback(fieldId, '', true);
            return;
        }
        if (username.length < 4) {
            isUsernameAjaxValid = false
            setFeedback(fieldId, 'Minimum 4 caractères.', false);
            return;
        }
        if (username.length > 24) {
            isUsernameAjaxValid = false
            setFeedback(fieldId, 'Maximum 24 caractères.', false);
            return;
        }
        if (!/^[a-zA-Z0-9_-]+$/.test(username)) {
            isUsernameAjaxValid = false
            setFeedback(fieldId, 'Caractères autorisés : lettres, chiffres, underscore.', false);
            return;
        }

        setFeedback(fieldId, '', true);

        usernameTimeout = setTimeout(function() {
            $.ajax({
                url: "./admin/src/php/ajax/ajax_check_username.php",
                type: 'GET',
                data: { username: username },
                dataType: 'json',
                success: function(response) {
                    isUsernameAjaxValid = response.isValid;
                    setFeedback(fieldId, response.message, response.isValid);
                },
                error: function() {
                    setFeedback(fieldId, 'Erreur de communication serveur.', false);
                }
            });
        }, debounceTime);
    });

    $('#email').on('input keyup', function() {
        clearTimeout(emailTimeout);
        const email = $(this).val().trim();
        const fieldId = 'email';

        if (email.length === 0) {
            isEmailAjaxValid = false;
            setFeedback(fieldId, '', true);
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            isEmailAjaxValid = false;
            setFeedback(fieldId, 'Format d\'email invalide.', false);
            return;
        }

        setFeedback(fieldId, '', true);

        emailTimeout = setTimeout(function() {
            $.ajax({
                url: "./admin/src/php/ajax/ajax_check_email.php",
                type: 'GET',
                data: { email: email },
                dataType: 'json',
                success: function(response) {
                    isEmailAjaxValid = response.isValid;
                    setFeedback(fieldId, response.message, response.isValid);
                },
                error: function() {
                    setFeedback(fieldId, 'Erreur de communication serveur.', false);
                }
            });
        }, debounceTime);
    });

});