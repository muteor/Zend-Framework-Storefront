<?php
/** Storefront_Resource_Product_Interface */
require_once dirname(__FILE__) . '/Item/Interface.php';

/**
 * Storefront_Resource_Product_Item
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Resource_Product_Item extends SF_Model_Resource_Db_Table_Row_Abstract implements Storefront_Resource_Product_Item_Interface
{   
    /**
     * Get product images
     *
     * @param  boolean $includeDefault Whether to include the default
     * @return array Containing Storefront_Resource_ProductImage_Item
     */
    public function getImages($includeDefault=false)
    {
        $select = $this->select();
        if (false === $includeDefault) {
            $select->where('isDefault != ?', 'Yes');
        }
        return $this->findDependentRowset('Storefront_Resource_ProductImage',
            'Image', 
            $select
        );
    }
    
    /**
     * Get the default image
     *
     * @return Storefront_Resource_ProductImage_Item
     */
    public function getDefaultImage()
    {
        $row = $this->findDependentRowset('Storefront_Resource_ProductImage', 
            'Image', 
            $this->select()
                 ->where('isDefault = ?', 'Yes')
                 ->limit(1)
        )->current();
        
        return $row;
    }
    
    /**
     * Get the price
     *
     * @param  boolean $withDiscount Include discount calculation
     * @param  boolean $withTax      Include tax calculation
     * @return string The products price
     */
    public function getPrice($withDiscount=true,$withTax=true)
    {
        $price = $this->getRow()->price;
        if (true === $this->isDiscounted() && true === $withDiscount) {
            $discount = $this->getRow()->discountPercent;
            $discounted = ($price*$discount)/100;
            $price = round($price - $discounted, 2);
        }
        if (true === $this->isTaxable() && true === $withTax) {
            $taxService = new Storefront_Service_Taxation();
            $price = $taxService->addTax($price);
        }
        return $price;
    }
    
    /**
     * Is this product discounted ?
     *
     * @return boolean
     */
    public function isDiscounted()
    {
        return 0 == $this->getRow()->discountPercent ? false : true;
    }
    
    /**
     * Is this product taxable?
     *
     * @return boolean
     */
    public function isTaxable()
    {
        return 'Yes' == $this->getRow()->taxable ? true : false;
    }
}