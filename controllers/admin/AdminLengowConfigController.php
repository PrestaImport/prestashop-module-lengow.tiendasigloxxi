<?php
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

/**
 * The Lengow's Configuration Admin Controller.
 *
 */
class AdminLengowConfigController extends ModuleAdminController
{

    /**
     * Construct the admin selection of products
     */
    public function __construct()
    {
        $this->table = 'product';
        $this->context = Context::getContext();
        $this->lang = true;
        $this->explicitSelect = true;
        $this->lite_display = true;
        $this->meta_title = 'Configuration';
        $this->list_no_link = true;
        if (_PS_VERSION_ >= '1.6') {
            $this->bootstrap = true;
        }
        $this->template = 'layout.tpl';
        $this->display = 'view';

        parent::__construct();

        $this->lengowConfig = new LengowConfig();
        $this->lengowConfig->postProcessForm();
        $this->lengowConfig->displayForm();

        //$this->postProcessForm();
        //$this->displayForm();
    }
}