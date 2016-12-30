<?php
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
 * @category  Lengow_Controller
 * @package   LengowMainSettingController
 * @author    Team Connector <team-connector@lengow.com>
 * @copyright 2017 Lengow SAS
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

class LengowMainSettingController extends LengowController
{
    /**
     * Process Post Parameters
     */
    public function postProcess()
    {
        $action = Tools::getValue('action');
        switch ($action) {
            case 'process':
                $security = LengowMain::decodeLogMessage('global_setting.screen.i_am_sure');
                if (isset($_REQUEST['uninstall_textbox']) &&
                    trim($_REQUEST['uninstall_textbox']) == $security
                ) {
                    $backup = new LengowBackup();
                    if ($backup->add()) {
                        LengowConfiguration::deleteAll();
                        LengowInstall::dropTable();
                        $module = Module::getInstanceByName('lengow');
                        $module->uninstall();
                        $link = new LengowLink();
                        $configLink = $link->getAbsoluteAdminLink('AdminModules', false, true);
                        Tools::redirect($configLink.'&conf=13', '');
                    }
                }
                $form = new LengowConfigurationForm(
                    array(
                        "fields" => LengowConfiguration::getKeys(),
                    )
                );
                $form->postProcess(
                    array(
                        'LENGOW_REPORT_MAIL_ENABLED',
                        'LENGOW_REPORT_MAIL_ADDRESS',
                        'LENGOW_IMPORT_PREPROD_ENABLED',
                        'LENGOW_SHOP_ACTIVE',
                    )
                );
                break;
            case 'download':
                $file = isset($_REQUEST['file']) ?  $_REQUEST['file'] : null;
                LengowLog::download($file);
                break;
            case 'download_all':
                LengowLog::download();
                break;
        }
    }

    /**
     * Display data page
     */
    public function display()
    {
        $form = new LengowConfigurationForm(
            array(
                "fields" => LengowConfiguration::getKeys(),
            )
        );
        $form->fields['LENGOW_REPORT_MAIL_ADDRESS']['label'] = '';
        $mailReport = $form->buildInputs(
            array(
                'LENGOW_REPORT_MAIL_ENABLED',
                'LENGOW_REPORT_MAIL_ADDRESS',
            )
        );
        $preprodReport = $form->buildInputs(
            array(
                'LENGOW_IMPORT_PREPROD_ENABLED',
            )
        );
        $preprodWrapper = '';
        $shops = LengowShop::findAll(true);
        foreach ($shops as $s) {
            $shop = new LengowShop($s['id_shop']);
            $form->fields['LENGOW_SHOP_ACTIVE']['label'] = $shop->name;
            $preprodWrapper.= '<div class="grey-frame">'.$form->buildShopInputs(
                $shop->id,
                array(
                    'LENGOW_SHOP_ACTIVE',
                    'LENGOW_ACCOUNT_ID',
                    'LENGOW_ACCESS_TOKEN',
                    'LENGOW_SECRET_TOKEN',
                )
            ).'</div>';
        }
        $listFile = LengowLog::getPaths();
        $this->context->smarty->assign('list_file', $listFile);
        $this->context->smarty->assign('mail_report', $mailReport);
        $this->context->smarty->assign('preprod_report', $preprodReport);
        $this->context->smarty->assign('preprod_wrapper', $preprodWrapper);
        parent::display();
    }
}
