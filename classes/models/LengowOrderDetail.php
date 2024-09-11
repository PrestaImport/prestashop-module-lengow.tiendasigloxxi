<?php
/**
 * Copyright 2021 Lengow SAS.
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
 * @copyright 2021 Lengow SAS
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */
/*
 * Lengow Order Detail Class
 */
if (!defined('_PS_VERSION_')) {
    exit;
}
class LengowOrderDetail extends OrderDetail
{
    /**
     * Get Order Lines
     *
     * @param int $idOrder PrestaShop order id
     * @param int|string $idProduct PrestaShop product id
     *
     * @return int
     */
    public static function findByOrderIdProductId($idOrder, $idProduct)
    {
        $whereArr = [
            '`id_order`=' . (int) $idOrder,
            '`product_id`=' . (int) $idProduct,
        ];

        // Divide the product ID using the underscore character as a separator
        $ids = explode('_', (string) $idProduct);

        // If a second element exists in the $ids array, it is considered the ID of the product attribute
        if (isset($ids[1])) {
            $productAttributeId = (int) $ids[1];
            // Add condition for product attribute ID
            $whereArr[] = '`product_attribute_id`=' . $productAttributeId;
        }

        $sql = 'SELECT `id_order_detail` FROM `' . _DB_PREFIX_ . 'order_detail` WHERE ' . implode(' AND ', $whereArr);

        return (int) Db::getInstance()->getValue($sql);
    }

    /**
     * @param string $returnTrackingNumber
     * @param int $orderId
     */
    public static function updateOrderReturnTrackingNumber($returnTrackingNumber, $orderId)
    {
        try {
            $returnTrackingNumber = pSQL($returnTrackingNumber);
            $order = new Order($orderId);
            $orderCarrier = new LengowOrderCarrier((int) $order->getIdOrderCarrier());
            $orderCarrier->return_tracking_number = $returnTrackingNumber;
            $orderCarrier->update();
        } catch (Exception $e) {
            LengowOrderError::addOrderLog(
                $orderId,
                '[PrestaShop error]: ' . $e->getMessage(),
                LengowOrderError::TYPE_ERROR_SEND
            );
        }
    }

    /**
     * @param string $returnTrackingNumber
     * @param int $orderId
     */
    public static function updateOrderReturnCarrier($returnCarrier, $orderId)
    {
        try {
            $returnCarrier = pSQL($returnCarrier);
            $order = new Order($orderId);
            $orderCarrier = new LengowOrderCarrier((int) $order->getIdOrderCarrier());
            $orderCarrier->return_carrier = $returnCarrier;
            $orderCarrier->update();
        } catch (Exception $e) {
            LengowOrderError::addOrderLog(
                $orderId,
                '[PrestaShop error]: ' . $e->getMessage(),
                LengowOrderError::TYPE_ERROR_SEND
            );
        }
    }

    /**
     * @param int $orderId
     *
     * @return string
     */
    public static function getOrderReturnTrackingNumber($orderId)
    {
        try {
            $order = new Order($orderId);
            $orderCarrier = new LengowOrderCarrier((int) $order->getIdOrderCarrier());

            return (string) $orderCarrier->return_tracking_number;
        } catch (Exception $e) {
            LengowOrderError::addOrderLog(
                $orderId,
                '[PrestaShop error]: ' . $e->getMessage(),
                LengowOrderError::TYPE_ERROR_SEND
            );
        }

        return '';
    }

    /**
     * @param int $orderId
     *
     * @return string
     */
    public static function getOrderReturnCarrier($orderId)
    {
        try {
            $order = new Order($orderId);
            $orderCarrier = new LengowOrderCarrier((int) $order->getIdOrderCarrier());

            return (string) $orderCarrier->return_carrier;
        } catch (Exception $e) {
            LengowOrderError::addOrderLog(
                $orderId,
                '[PrestaShop error]: ' . $e->getMessage(),
                LengowOrderError::TYPE_ERROR_SEND
            );
        }

        return '';
    }

    /**
     * @param int $orderId
     *
     * @return string
     */
    public static function getOrderReturnCarrierName($orderId)
    {
        try {
            $order = new Order($orderId);
            $orderCarrier = new LengowOrderCarrier((int) $order->getIdOrderCarrier());
            $carrier = new LengowCarrier($orderCarrier->return_carrier);

            return (string) $carrier->name;
        } catch (Exception $e) {
            LengowOrderError::addOrderLog(
                $orderId,
                '[PrestaShop error]: ' . $e->getMessage(),
                LengowOrderError::TYPE_ERROR_SEND
            );
        }

        return '';
    }

    /**
     * [2021-11-23] - (josecarlosphp.com)
     * Forzar parámetro $use_taxes a false si el total del producto es igual al total con impuestos.
     */
    protected function create(Order $order, Cart $cart, $product, $id_order_state, $id_order_invoice, $use_taxes = true, $id_warehouse = 0)
    {
        return parent::create($order, $cart, $product, $id_order_state, $id_order_invoice, $use_taxes && (round($product['total']) != round($product['total_wt'])), $id_warehouse);
    }

    /**
     * [2021-11-23] - (josecarlosphp.com)
     * Forzar tax_name en blanco, tax_rate a cero, y tax_calculator a null,
     * si el total del producto es igual al total con impuestos.
     */
    protected function setProductTax(Order $order, $product)
    {
        parent::setProductTax($order, $product);

        if ((round($product['total']) == round($product['total_wt']))) {
            $this->tax_name = '';
            $this->tax_rate = 0;
            $this->tax_calculator = null;
        }
    }
}
