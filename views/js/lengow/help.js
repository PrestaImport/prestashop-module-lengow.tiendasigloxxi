/**
 * Copyright 2016 Lengow SAS.
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
 * @copyright 2016 Lengow SAS
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

(function ($) {
    $(document).ready(function () {

        /*var href = $('#lengow_ajax_link').val();

        var sync_iframe = document.getElementById('lengow_help_iframe');
        sync_iframe.onload = function() {
            $.ajax({
                url: href,
                method: 'POST',
                data: {action: 'get_help_data'},
                dataType: 'json',
                success: function(data) {
                    var targetFrame = document.getElementById('lengow_help_iframe').contentWindow;
                    targetFrame.postMessage(data, '*');
                }
            });
        };
        // sync_iframe.src = 'http://my.lengow.local/help/ticket/cms_new';
        sync_iframe.src = '/modules/lengow/webservice/help.php';

        resize();

        $(window).on('resize', function(){
            resize();
        });

        function resize() {
            $('#lengow_help_wrapper').height($('body').height());
        }*/

        window.addEventListener("message", receiveMessage, false);
         
        function receiveMessage(event) {
            //if (event.origin !== "http://my.lengow.io")
            //    return;
            switch (event.data.function) {
                case 'sync':
                    console.log(event.data.parameters);
                    break;
            }
        }

    });
})(lengow_jquery);