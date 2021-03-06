import './bootstrap'
import $ from 'jquery'

/*====================================
=            ON DOM READY            =
====================================*/
$(function() {
    $('.toggle-nav').click(function() {
        toggleNav();
    });
});

/*========================================
=            CUSTOM FUNCTIONS            =
========================================*/

function showNavigation() {
    $('.site-wrapper').addClass('show-nav');
    var overlay = $('#overlay_dark');
    overlay.fadeIn('fast');
    overlay.on('click', function(){
        hideNavigation();
    });
}

function hideNavigation() {
    $('.site-wrapper').removeClass('show-nav');
    var overlay = $('#overlay_dark');
    overlay.fadeOut('fast');
    overlay.off('click');
}

function toggleNav() {
    if ($('.site-wrapper').hasClass('show-nav')) {
        // Do things on Nav Close
        hideNavigation();
    } else {
        // Do things on Nav Open
        showNavigation();
    }
}

$(function(){

    // Delete confirmation method
    $( '.delete-confirmation' ).on('click', function(){
        return confirm( $(this).attr( 'data-confirmation' ) );
    });

    //  Context navigation
    $('.context-nav-toggle').on('click', function(){
        var nav = $(this).siblings('.context-nav');
        var overlay = $('#overlay');
        if (nav.is(":visible")) {
            nav.fadeOut('fast');
            overlay.fadeOut('fast');
        } else {
            nav.fadeIn('fast');
            overlay.fadeIn('fast');
            overlay.on('click', function(){
                if ($('.context-nav').is(":visible")) {
                    nav.fadeOut('fast');
                    overlay.fadeOut('fast');
                }
            });
        }
    });

});

// Elements with the selector class gain focus and have their cursor set to the end
$(function(){
    $('.focus-tail').each(function(){
        var value = $(this).val();
        $(this).val('').focus().val(value);
    });
});

//
// Snackbar
//
import Snackbar from 'node-snackbar'

$(function(){
    $('.snack-message').each(function() {
        Snackbar.show({
            text: $(this).html(),
            duration: $(this).data('duration') ? $(this).data('duration') : 2500,
            pos: 'bottom-center',
            actionText: $(this).data('action') ? $(this).data('action') : null,
            actionTextColor: null,
            customClass: $(this).data('class'),
        });
    });
});

// Lity Lightbox
import 'lity'

/**
 * Tags input
 */
import Tagify from '@yaireo/tagify'
var tagifyAjaxController; // for aborting the call
$(document).ready(function () {
    document.querySelectorAll('input.tags').forEach((input) => {
        var suggestions = input.getAttribute('data-suggestions') != null ? JSON.parse(input.getAttribute('data-suggestions')) : [];
        var tagify = new Tagify(input, {
            whitelist: suggestions
        });

        var suggestionsUrl = input.getAttribute('data-suggestions-url');
        if (suggestionsUrl) {
            tagify.on('input', function(e){
                var value = e.detail;
                tagify.settings.whitelist.length = 0; // reset the whitelist

                // https://developer.mozilla.org/en-US/docs/Web/API/AbortController/abort
                tagifyAjaxController && tagifyAjaxController.abort();
                tagifyAjaxController = new AbortController();

                fetch(suggestionsUrl + value, {
                        signal: tagifyAjaxController.signal
                    })
                    .then(RES => RES.json())
                    .then(function(whitelist){
                        tagify.settings.whitelist = whitelist;
                        tagify.dropdown.show.call(tagify, value); // render the suggestions dropdown
                    })

            });
        }

    });
});

/**
 * Method for sending post request
 */
window.postRequest = function(path, params, method) {
    method = method || "post"; // Set method to post by default if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    // CSRF token
    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        params._token = token.content;
    }

    for (var key in params) {
        if (Object.prototype.hasOwnProperty.call(params, key)) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}

//
// Share an URL
//
$(function(){
    $('[rel="share-url"]').on('click', function() {
        var url = $(this).data('url');
        if (navigator.share) {
            navigator.share({
                    title: document.title,
                    url: url,
                })
                .then(() => console.log('Successful share'))
                .catch((error) => console.log('Error sharing', error));
        } else {
            var dummy = $('<input>').val(url).appendTo('body').select();
            document.execCommand('copy');
            dummy.remove();
            Snackbar.show({
                text: 'Copied URL to clipboard.',
                duration: 2500,
                pos: 'bottom-center',
            });
        }
    });
});
