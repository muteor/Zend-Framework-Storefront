<?php
/**
 * Zend_View_Helper_Shipping
 *
 * Helper to shipping related display
 *
 * @category   Storefront
 * @package    Storefront_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Zend_View_Helper_Shipping extends Zend_View_Helper_Abstract
{
    public $shippingModel;

    public function Shipping()
    {
        $this->shippingModel->getShippingOptions();
        return $this;
    }

    public function getShippingOptions()
    {
        return $this->view->formSelect();
    }
}
