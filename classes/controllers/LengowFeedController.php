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

class LengowFeedController extends LengowController
{
    protected $list;

    /**
     * Update data
     */
    public function postProcess()
    {
        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : false;
        if ($action) {
            switch ($action) {
                case 'change_option_product_variation':
                    $state = isset($_REQUEST['state']) ? $_REQUEST['state'] : null;
                    $shopId = isset($_REQUEST['id_shop']) ? (int)$_REQUEST['id_shop'] : null;
                    if ($state !== null) {
                        Configuration::updatevalue('LENGOW_EXPORT_VARIATION_ENABLED', $state, null, null, $shopId);
                        $this->reloadTotal($shopId);
                    }
                    break;
                case 'change_option_selected':
                    $state = isset($_REQUEST['state']) ? $_REQUEST['state'] : null;
                    $shopId = isset($_REQUEST['id_shop']) ? (int)$_REQUEST['id_shop'] : null;
                    if ($state !== null) {
                        Configuration::updatevalue('LENGOW_EXPORT_SELECTION_ENABLED', $state, null, null, $shopId);
                        $this->reloadTotal($shopId);
                        $state = Configuration::get('LENGOW_EXPORT_SELECTION_ENABLED', null, null, $shopId);
                        if ($state) {
                            echo "lengow_jquery('#block_" . $shopId . " .lengow_feed_block_footer_content').show();";
                        } else {
                            echo "lengow_jquery('#block_" . $shopId . " .lengow_feed_block_footer_content').hide();";
                        }
                    }
                    break;
                case 'change_option_product_out_of_stock':
                    $state = isset($_REQUEST['state']) ? $_REQUEST['state'] : null;
                    $shopId = isset($_REQUEST['id_shop']) ? (int)$_REQUEST['id_shop'] : null;
                    if ($state !== null) {
                        Configuration::updatevalue('LENGOW_EXPORT_OUT_STOCK', $state, null, null, $shopId);
                        $this->reloadTotal($shopId);
                    }
                    break;
                case 'select_product':
                    $state = isset($_REQUEST['state']) ? $_REQUEST['state'] : null;
                    $shopId = isset($_REQUEST['id_shop']) ? (int)$_REQUEST['id_shop'] : null;
                    $productId = isset($_REQUEST['id_product']) ? $_REQUEST['id_product'] : null;
                    if ($state !== null) {
                        LengowProduct::publish($productId, $state, $shopId);
                        $this->reloadTotal($shopId);
                    }
                    break;
                case 'load_table':
                    $shopId = isset($_REQUEST['id_shop']) ? (int)$_REQUEST['id_shop'] : null;
                    echo 'lengow_jquery("#block_'.$shopId.' .lengow_feed_block_footer_content").html("'.
                        preg_replace('/\r|\n/', '', addslashes($this->buildTable($shopId))).'");';
                    if ($this->toolbox) {
                        echo 'lengow_jquery(".lengow_switch").bootstrapSwitch({readonly: true});';
                    }
                    break;
                case 'add_to_export':
                    $shopId = isset($_REQUEST['id_shop']) ? (int)$_REQUEST['id_shop'] : null;
                    $selection = isset($_REQUEST['selection']) ? $_REQUEST['selection'] : null;
                    $select_all = isset($_REQUEST['select_all']) ? $_REQUEST['select_all'] : null;
                    if ($select_all == "true") {
                        $this->buildTable($shopId);
                        $sql = $this->list->buildQuery(false, true);
                        $db = Db::getInstance()->executeS($sql);
                        $all = array();
                        foreach ($db as $value) {
                            $all[] = $value['id_product'];
                        }
                        foreach ($all as $id) {
                            LengowProduct::publish($id, 1, $shopId);
                            foreach ($selection as $id => $v) {
                                echo 'lengow_jquery("#block_' . $shopId . ' .lengow_product_selection_' . $id . '")';
                                echo '.bootstrapSwitch("state",true, true);';
                            }
                        }

                        $this->reloadTotal($shopId);
                    } elseif ($selection) {
                        foreach ($selection as $id => $v) {
                            // This line is useless, but Prestashop validator require it
                            $v = $v;
                            LengowProduct::publish($id, 1, $shopId);
                            echo 'lengow_jquery("#block_' . $shopId . ' .lengow_product_selection_' . $id . '")';
                            echo '.bootstrapSwitch("state",true, true);';
                        }
                        $this->reloadTotal($shopId);
                    } else {
                        echo 'alert("' . $this->locale->t('product.screen.no_product_selected') . '");';
                    }
                    break;
                case 'remove_from_export':
                    $shopId = isset($_REQUEST['id_shop']) ? (int)$_REQUEST['id_shop'] : null;
                    $selection = isset($_REQUEST['selection']) ? $_REQUEST['selection'] : null;
                    $select_all = isset($_REQUEST['select_all']) ? $_REQUEST['select_all'] : null;
                    if ($select_all == "true") {
                        $this->buildTable($shopId);
                        $sql = $this->list->buildQuery(false, true);
                        $db = Db::getInstance()->executeS($sql);
                        $all = array();
                        foreach ($db as $value) {
                            $all[] = $value['id_product'];
                        }
                        foreach ($all as $id) {
                            LengowProduct::publish($id, 0, $shopId);
                            foreach ($selection as $id => $v) {
                                echo 'lengow_jquery("#block_' . $shopId . ' .lengow_product_selection_' . $id . '")';
                                echo '.bootstrapSwitch("state",false, true);';
                            }
                        }
                        $this->reloadTotal($shopId);
                    } elseif ($selection) {
                        foreach ($selection as $id => $v) {
                            LengowProduct::publish($id, 0, $shopId);
                            echo 'lengow_jquery("#block_'.$shopId.' .lengow_product_selection_'.$id.'")';
                            echo '.bootstrapSwitch("state",false, true);';
                        }
                        $this->reloadTotal($shopId);
                    } else {
                        echo 'alert("' . $this->locale->t('product.screen.no_product_selected') . '");';
                    }
                    break;
                case 'check_shop':
                    $shops = LengowShop::findAll(true);
                    $link = new LengowLink();
                    foreach ($shops as $shopId) {
                        if ($this->checkShop($shopId['id_shop']) === true) {
                            echo 'lengow_jquery("#block_' . $shopId['id_shop'] . ' .lengow_check_shop").attr("id", "lengow_shop_sync");';
                            echo 'lengow_jquery("#block_' . $shopId['id_shop'] . ' .lengow_check_shop").attr("data-original-title", "'
                                    . $this->locale->t('product.screen.lengow_shop_sync') . '");';
                            echo 'lengow_jquery("#block_' . $shopId['id_shop'] . ' .lengow_check_shop").html("");';
                        } else {
                            echo 'lengow_jquery("#block_' . $shopId['id_shop'] . ' .lengow_check_shop").attr("id", "lengow_shop_no_sync");';
                            echo 'lengow_jquery("#block_' . $shopId['id_shop'] . ' .lengow_check_shop").attr("data-original-title", "'
                                . $this->locale->t('product.screen.lengow_shop_no_sync') . '");';
                            echo 'lengow_jquery("#block_' . $shopId['id_shop'] . ' .lengow_check_shop").html("");';
                            echo 'lengow_jquery("#block_' . $shopId['id_shop'] . ' .lengow_feed_block_header_title").append("<a href=\"'
                                . $link->getAbsoluteAdminLink('AdminLengowHome', true) . '&isSync=true\" ><span>sync</span></a>");';
                        }
                    }
                    break;
            }
            exit();
        }
    }

    /**
     * Display data page
     */
    public function display()
    {
        $shopCollection = array();
        if (_PS_VERSION_ < '1.5') {
            $results = array(array('id_shop' => 1));
        } else {
            if ($currentShop = Shop::getContextShopID()) {
                $results = array(array('id_shop' => $currentShop));
            } else {
                $sql = 'SELECT id_shop FROM '._DB_PREFIX_.'shop WHERE active = 1';
                $results = Db::getInstance()->ExecuteS($sql);
            }
        }
        foreach ($results as $row) {
            $shop = new LengowShop($row['id_shop']);
            $lengowExport = new LengowExport(array(
                "shop_id" => $shop->id
            ));
            $shopCollection[]= array(
                'shop' => $shop,
                'link' => LengowMain::getExportUrl($shop->id),
                'total_product' => $lengowExport->getTotalProduct(),
                'total_export_product' => $lengowExport->getTotalExportProduct(),
                'last_export' => Configuration::get('LENGOW_LAST_EXPORT', null, null, $shop->id),
                'option_selected' => Configuration::get('LENGOW_EXPORT_SELECTION_ENABLED', null, null, $shop->id),
                'option_variation' => Configuration::get('LENGOW_EXPORT_VARIATION_ENABLED', null, null, $shop->id),
                'option_product_out_of_stock' => Configuration::get('LENGOW_EXPORT_OUT_STOCK', null, null, $shop->id),
                'list' => $this->buildTable($shop->id)
            );
        }
        $this->context->smarty->assign('shopCollection', $shopCollection);
        parent::display();
    }

    /**
     * Check token shop
     * @param $idShop
     */
    public function checkShop($idShop)
    {
        $token = LengowConfiguration::get('LENGOW_SHOP_TOKEN', null, null, $idShop);
        $json_data = LengowShop::getContentShopJson();
        foreach ($json_data->shops as $key => $value) {
            if ($value->token == $token) {
                if ($value->enabled === "1") {
                    return true;
                }
            }
        }
        return false;

    }

    /**
     * Reload Total product / Exported product
     * @param $shopId
     */
    public function reloadTotal($shopId)
    {
        $lengowExport = new LengowExport(array(
            "shop_id" => $shopId
        ));
        echo 'lengow_jquery("#block_'.$shopId.' .lengow_exported").html("'.$lengowExport->getTotalExportProduct().'");';
        echo 'lengow_jquery("#block_'.$shopId.' .lengow_total").html("'.$lengowExport->getTotalProduct().'");';
    }

    /**
     * Reload Total product / Exported product
     * @param $shopId
     * @return string
     */
    public function buildTable($shopId)
    {
        $fields_list = array();

        $fields_list['id_product'] = array(
            'title'         => $this->locale->t('product.table.id_product'),
            'class'         => 'center',
            'width'         => '5%',
            'filter'        => true,
            'filter_order'  => true,
            'filter_key'    => 'p.id_product',
        );
        $fields_list['image'] = array(
            'title'         => $this->locale->t('product.table.image'),
            'class'         => 'center',
            'image'         => 'p',
            'width'         => '5%',
        );
        $fields_list['name'] = array(
            'title'         => $this->locale->t('product.table.name'),
            'filter'        => true,
            'filter_order'  => true,
            'filter_key'    => 'pl.name',
            'width'         => '20%',
        );
        $fields_list['reference'] = array(
            'title'         => $this->locale->t('product.table.reference'),
            'class'         => 'left',
            'width'         => '14%',
            'filter'        => true,
            'filter_order'  => true,
            'filter_key'    => 'p.reference',
            'display_callback'  => 'LengowFeedController::displayLink',
        );
        $fields_list['category_name'] = array(
            'title'         => $this->locale->t('product.table.category_name'),
            'width'         => '14%',
            'filter'        => true,
            'filter_order'  => true,
            'filter_key'    => 'cl.name',
        );
        $fields_list['price'] = array(
            'title'         => $this->locale->t('product.table.price'),
            'width'         => '9%',
            'filter_order'  => true,
            'type'          => 'price',
            'class'         => 'left',
            'filter_key'    => 'p.price'
        );
        $fields_list['price_final'] = array(
            'title'         => $this->locale->t('product.table.final_price'),
            'width'         => '7%',
            'type'          => 'price',
            'class'         => 'left',
            'havingFilter'  => true,
            'orderby'       => false
        );
        if (_PS_VERSION_ >= '1.5') {
            $quantity_filter_key = 'sav.quantity';
        } else {
            $quantity_filter_key = 'p.quantity';
        }
        $fields_list['quantity'] = array(
            'title'         => $this->locale->t('product.table.quantity'),
            'width'         => '7%',
            'filter_order'  => true,
            'class'         => 'left',
            'filter_key'    => $quantity_filter_key,
            'orderby'       => true,
        );
        $fields_list['id_lengow_product'] = array(
            'title'         => $this->locale->t('product.table.lengow_status'),
            'width'         => '10%',
            'class'         => 'center',
            'type'          => 'switch_product',
            'filter_order'  => true,
            'filter_key'    => 'id_lengow_product'
        );
        $fields_list['search'] = array(
            'title'         => '',
            'width'         => '12%',
            'button_search' => true
        );

        $join = array();
        $where = array();

        $select = array(
            "p.id_product",
            "p.reference",
            "p.price",
            "pl.name",
            "0 as price_final",
            "IF(lp.id_product, 1, 0) as id_lengow_product",
            "cl.name as category_name",
            "'' as search"
        );
        $from = 'FROM '._DB_PREFIX_.'product p';

        $join[] = ' INNER JOIN '._DB_PREFIX_.'product_lang pl ON (pl.id_product = p.id_product
        AND pl.id_lang = 1 '.(_PS_VERSION_ < '1.5' ? '': ' AND pl.id_shop = '.(int)$shopId).')';
        $join[] = ' LEFT JOIN '._DB_PREFIX_.'lengow_product lp ON (lp.id_product = p.id_product
        AND lp.id_shop = '.(int)$shopId.' ) ';
        if (_PS_VERSION_ >= '1.5') {
            $join[] = 'INNER JOIN `'._DB_PREFIX_.'product_shop` ps ON (p.`id_product` = ps.`id_product`
            AND ps.id_shop = ' . (int)$shopId . ') ';
            $join[] = ' LEFT JOIN '._DB_PREFIX_.'stock_available sav ON (sav.id_product = p.id_product
            AND sav.id_product_attribute = 0 AND sav.id_shop = ' . (int)$shopId . ')';
        }
        if (_PS_VERSION_ >= '1.5') {
            if (Shop::isFeatureActive()) {
                $join[] = 'LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
                ON (ps.`id_category_default` = cl.`id_category`
                AND pl.`id_lang` = cl.`id_lang` AND cl.id_shop = ' . (int)$shopId . ')';
                $join[] = 'LEFT JOIN `'._DB_PREFIX_.'shop` shop ON (shop.id_shop = ' . (int)$shopId . ') ';
            } else {
                $join[] = 'LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
                ON (p.`id_category_default` = cl.`id_category`
                AND pl.`id_lang` = cl.`id_lang` AND cl.id_shop = 1)';
            }
            $select[] = ' sav.quantity ';
            $where[] = ' ps.active = 1 ';
        } else {
            $join[] = 'LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.`id_category_default` = cl.`id_category`
            AND pl.`id_lang` = cl.`id_lang`)';
            $select[] = ' p.quantity ';
            $where[] = ' p.active = 1 ';
        }

        $currentPage = isset($_REQUEST['p']) ? $_REQUEST['p'] : 1;
        $orderValue = isset($_REQUEST['order_value']) ? $_REQUEST['order_value'] : '';
        $orderColumn = isset($_REQUEST['order_column']) ? $_REQUEST['order_column'] : '';

        $this->list = new LengowList(array(
            "id"            => 'shop_'.$shopId,
            "fields_list"   => $fields_list,
            "identifier"    => 'id_product',
            "selection"     => true,
            "controller"    => 'AdminLengowFeed',
            "shop_id"       => $shopId,
            "current_page"  => $currentPage,
            "ajax"          => true,
            "order_value"   => $orderValue,
            "order_column"  => $orderColumn,
            "sql"           => array(
                "select" => $select,
                "from" => $from,
                "join" => $join,
                "where" => $where,
            )
        ));

        $collection = $this->list->executeQuery();

        $tempContext = new Context();
        $tempContext->shop = new Shop($shopId);
        $tempContext->employee = $this->context->employee;
        $tempContext->country = $this->context->country;

        //calcul price
        $nb = count($collection);
        if ($collection) {
            for ($i = 0; $i < $nb; $i++) {
                $productId = $collection[$i]['id_product'];
                if (_PS_VERSION_ < '1.5') {
                    $collection[$i]['price_final'] = Product::getPriceStatic(
                        $productId,
                        true,
                        null,
                        2,
                        null,
                        false,
                        true,
                        1,
                        true
                    );
                } else {
                    $nothing = '';
                    $collection[$i]['price_final'] = Product::getPriceStatic(
                        $productId,
                        true,
                        null,
                        2,
                        null,
                        false,
                        true,
                        1,
                        true,
                        null,
                        null,
                        null,
                        $nothing,
                        true,
                        true,
                        $tempContext
                    );
                }
                $collection[$i]['image'] = '';
                if (_PS_VERSION_ < '1.5') {
                    $coverImage = Product::getCover($productId);
                    if ($coverImage) {
                        $id_image = $coverImage['id_image'];
                        $imageProduct = new Image($id_image);
                        $collection[$i]['image'] = cacheImage(
                            _PS_IMG_DIR_.'p/'.$imageProduct->getExistingImgPath().'.jpg',
                            'product_mini_'.(int)($productId).'.jpg',
                            45,
                            'jpg'
                        );
                    }
                } else {
                    $coverImage = Product::getCover($collection[$i]['id_product'], $tempContext);
                    if ($coverImage) {
                        $id_image = $coverImage['id_image'];
                        $path_to_image = _PS_IMG_DIR_.'p/'.Image::getImgFolderStatic($id_image).(int)$id_image.'.jpg';
                        $collection[$i]['image'] = ImageManager::thumbnail(
                            $path_to_image,
                            'product_mini_'.$collection[$i]['id_product'].'_'.$shopId.'.jpg',
                            45,
                            'jpg'
                        );
                    }
                }
            }
        }
        $this->list->updateCollection($collection);
        $paginationBlock = $this->list->renderPagination(array(
            'nav_class' => 'lengow_feed_pagination'
        ));

        $lengow_link = new LengowLink();

        $html='<div class="lengow_table_top">';
        $html.='<div class="lengow_toolbar">';
        if (!$this->toolbox) {
            $html.='<a href="#" data-id_shop="'.$shopId.'" style="display:none;"
                data-href="'.$lengow_link->getAbsoluteAdminLink('AdminLengowFeed', true).'"
                data-message="'.$this->locale->t('product.screen.remove_confirmation', array(
                    'nb' => $this->list->getTotal()
                )).'"
                class="lengow_btn lengow_remove_from_export">
                <i class="fa fa-minus"></i> '.$this->locale->t('product.screen.remove_from_export').'</a>';
            $html.='<a href="#" data-id_shop="'.$shopId.'" style="display:none;"
                data-href="'.$lengow_link->getAbsoluteAdminLink('AdminLengowFeed', true).'"
                data-message="'.$this->locale->t('product.screen.add_confirmation', array(
                    'nb' => $this->list->getTotal()
                )).'"
                class="lengow_btn lengow_add_to_export">
                <i class="fa fa-plus"></i> '.$this->locale->t('product.screen.add_from_export').'</a>';
            $html.='<div class="lengow_select_all_shop" style="display:none;">';
            $html.='<input type="checkbox" id="select_all_shop_'.$shopId.'"/>';
            $html.='<span>'.$this->locale->t('product.screen.select_all_products', array(
                        'nb' => $this->list->getTotal()
                    ));
            $html.='</span>';
            $html.='</div>';
        }
        $html.='</div>';
        $html.= $paginationBlock;
        $html.='<div class="lengow_clear"></div>';
        $html.='</div>';
        $html.= $this->list->display();
        $html.='<div class="lengow_table_bottom">';
        $html.= $paginationBlock;
        $html.='<div class="lengow_clear"></div>';
        $html.='</div>';

        return $html;
    }

    public static function displayLink($key, $value, $item)
    {
        // This line is useless, but Prestashop validator require it
        $key = $key;
        $toolbox = Context::getContext()->smarty->getVariable('toolbox')->value;
        $link = new LengowLink();
        if ($item['id_product']) {
            if (!$toolbox) {
                return '<a href="'.
                $link->getAbsoluteAdminLink((_PS_VERSION_ < '1.5' ? 'AdminCatalog' : 'AdminProducts'), false, true).
                '&updateproduct&id_product='.
                $item['id_product'].'" target="_blank">'.$value.'</a>';
            } else {
                return $value;
            }
        } else {
            return $value;
        }
    }
}
