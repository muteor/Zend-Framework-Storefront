<?php
/**
 * Storefront_Service_Cart
 *
 * Extends the cart Model to provide Ajax JSON interface
 *
 * @category   Storefront
 * @package    Storefront_Service
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Service_Cart
{
    protected $_cartModel;

    public function __construct()
    {
        $this->_cartModel = new Storefront_Model_Cart();
    }

    public function getItems()
    {
        $items = array();

        foreach($this->_cartModel as $item) {
            $items[] = array(
                'name'      => $item->name,
                'productId' => $item->productId,
                'lineCost'  => $item->getLineCost(),
            );
        }

        return Zend_Json::encode($items);
    }
}
