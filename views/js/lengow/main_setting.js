/**
 * Copyright 2017 Lengow SAS.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 *
 * @author    Team Connector <team-connector@lengow.com>
 * @copyright 2017 Lengow SAS
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

function displayPreProdMode() {
    var selector = $('#lengow_wrapper_preprod');
    if ($("input[name='LENGOW_IMPORT_PREPROD_ENABLED']").prop('checked')) {
        selector.slideDown(150);
        var divLegend = selector.find('.legend');
        divLegend.addClass("blue-frame");
        divLegend.css('display', 'block');
        divLegend.show();
    } else {
        selector.slideUp(150);
        selector.find('.legend').hide();
    }
}

function displayReportMail() {
    var selector = $('.lengow_report_mail_address');
    if($('input[name="LENGOW_REPORT_MAIL_ENABLED"]').prop('checked')){
        selector.slideDown(150);
        var divLegend = selector.next('.legend');
        divLegend.addClass("blue-frame");
        divLegend.css('display', 'block');
        divLegend.show();
    }
    else{
        selector.slideUp(150);
        selector.next('.legend').hide();
    }
}

function formatState(state) {
    var image = $(state.element).data('image');
    if (!state.id) { return state.text; }
    if (!image) {
        return state.text;
    } else {
        var $state = $(
            '<span><img width="22" height="15" src="'+ image +'" class="img-flag" /> ' + state.text + '</span>'
        );
        return $state;
    }
}

function killModal(){
    window.location.hash = '';
    $('body').removeClass('unscrollable');
    $('.lgw-modal').removeClass('open');
    $('.js-confirm-delete').val('');
    $('.lengow_submit_delete_module')
        .addClass('lgw-btn-disabled')
        .removeClass('lgw-btn-red');
}

function openModal(){
    window.location.hash = 'delete';
    $('body').addClass('unscrollable');
    $('.lgw-modal').addClass('open');
}

(function ($) {
    $(document).ready(function () {
        // MODAL
        // Open modal
        $('.lgw-modal-delete').click(function(){
            window.location.hash = 'delete';
            return false;
        });
        // Open modal on loading
        if(window.location.hash) {
            openModal();
        }
        // Delete modal
        $('.js-close-this-modal').click(function(){
            window.location.hash = '';
            return false;
        });
        // Check hash modal
        var hash = window.location.hash;
        setInterval(function(){
            if (window.location.hash != hash) {
                hash = window.location.hash;
                if( hash.length < 1){
                    killModal();
                }
                else{
                    if( $('.lgw-modal.open').length == 0 ){
                        openModal();
                    }
                }
            }
        }, 100);
        // confirm delete modal
        $('.js-confirm-delete').keyup(function(){
            var confirm = $(this).data('confirm');
            if( $(this).val() == confirm ){
                $('.lengow_submit_delete_module')
                    .removeClass('lgw-btn-disabled')
                    .addClass('lgw-btn-red');
            }
            else{
                $('.lengow_submit_delete_module')
                    .addClass('lgw-btn-disabled')
                    .removeClass('lgw-btn-red');
            }
        });
        // display report mail
        displayReportMail();
        $('input[name="LENGOW_REPORT_MAIL_ENABLED"]').on('change', function(){
            displayReportMail();
        });
        // display preprod mode
        displayPreProdMode();
        $("input[name='LENGOW_IMPORT_PREPROD_ENABLED']").on('change', function () {
            displayPreProdMode();
        });
        // display log block
        $('#select_log').change(function(){
            if ($('#select_log').val() !== null) {
                $("#download_log" ).show();
            }
        });
        $('#download_log').on('click', function() {
            if ($('#select_log').val() !== null) {
                window.location.href = $('#select_log').val();
            }
        });
        // Submit form
        $( ".lengow_form" ).submit(function( event ) {
            event.preventDefault();
            var form = this;
            $('.lengow_form button[type="submit"]').addClass('loading');
            setTimeout(function () {
                $('.lengow_form button[type="submit"]').removeClass('loading');
                $('.lengow_form button[type="submit"]').addClass('success');
                form.submit();
            }, 1000);
        });
        // load select 2 format
        $(".lengow_select").select2({
            templateResult: formatState
        });
    });
})(lengow_jquery);