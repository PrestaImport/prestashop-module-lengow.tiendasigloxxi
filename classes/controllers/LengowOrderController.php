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

if (!defined('_PS_VERSION_')) {
    exit;
}

class LengowOrderController extends LengowController
{
    /**
     * Display data page
     */
    public function display()
    {
        $last_import =  LengowMain::getLastImport();

        $orderCollection = array(
            'last_import_date' => $last_import['date'],
            'last_import_type' => $last_import['type']
        );

        $this->context->smarty->assign('orderCollection', $orderCollection);
        parent::display();
    }
}