<?php
/**
 * Zend_View_Helper_ProductPrice
 * 
 * Helper for displaying product prices (human readable)
 * 
 * @category   Storefront
 * @package    Storefront_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Zend_View_Helper_ProductPrice extends Zend_View_Helper_Abstract 
{       
    public function productPrice(Storefront_Resource_Product_Item $product)
    {
        $currency = new Zend_Currency();
        $formatted = $currency->toCurrency($product->getPrice());
        
        if ($product->isDiscounted()) {
            $formatted .= ' was <del>' . $currency->toCurrency($product->getPrice(false)) . '</del>';
        }
        
        return $formatted;
    }    
}
