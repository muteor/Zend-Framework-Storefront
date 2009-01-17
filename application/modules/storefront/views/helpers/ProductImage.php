<?php
/**
 * Zend_View_Helper_ProductImage
 * 
 * Helper for getting product images
 * 
 * @category   Storefront
 * @package    Storefront_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Zend_View_Helper_ProductImage extends Zend_View_Helper_Abstract 
{
    protected $_product;
    protected $_default;
        
    public function productImage(Storefront_Resource_Product_Item $product, $default=false)
    {
        $this->_product = $product;
        $this->_default = $default;
        return $this;
    }
    
    public function thumbnail()
    {
        if (true === $this->_default) {
            $img = $this->_product->getDefaultImage();
            if (null !== $img) {
                return $img->thumbnail();
            }
        }
        
        return '';
    }
    
    public function full()
    {
        
    }
}
