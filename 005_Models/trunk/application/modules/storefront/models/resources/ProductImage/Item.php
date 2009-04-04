<?php
/**
 * Storefront_Resource_ProductImage_Item
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Resource_ProductImage_Item extends SF_Model_Resource_Db_Table_Row_Abstract implements Storefront_Resource_ProductImage_Item_Interface
{
    /**
     * Get the thumbnail
     *
     * @return string
     */
    public function getThumbnail()
    {
        return $this->getRow()->thumbnail;
    }
    
    /**
     * Get the full image
     *
     * @return string
     */
    public function getFull()
    {
        return $this->getRow()->full;
    }

    /**
     * Is this a default image
     * 
     * @return boolean 
     */
    public function isDefault()
    {
        return 'Yes' === $this->getRow()->isDefault ? true : false;
    }
}