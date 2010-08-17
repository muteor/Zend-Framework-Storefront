<?php
namespace Storefront\View\Helper;

use Zend\View\Helper,
    Zend\Currency,
    Storefront\Model;
/**
 * Storefront_View_Helper_ProductPrice
 * 
 * Helper for displaying product prices (human readable)
 * 
 * @category   Storefront
 * @package    Storefront_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class ProductPrice extends Helper\AbstractHelper
{
    public function direct(Storefront\Model\Resource\Product $product)
    {
        return $this->ProductPrice($product);
    }

    public function productPrice(Storefront\Model\Resource\Product $product)
    {
        $currency = new Currency\Currency();
        $formatted = $currency->toCurrency($product->getPrice());
        
        if ($product->isDiscounted()) {
            $formatted .= ' was <del>' . $currency->toCurrency($product->getPrice(false)) . '</del>';
        }
        
        return $formatted;
    }    
}
