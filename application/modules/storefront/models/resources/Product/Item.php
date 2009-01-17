<?php
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
    protected $_images;
    
    public function getImages($includeDefault=false)
    {
        $select = $this->select();
        if (false === $includeDefault) {
            $select->where('isDefault != ?', 'Yes');
        }
        $this->_images = $this->findDependentRowset('Storefront_Resource_ProductImage', 
            'Image', 
            $select
        )->toArray();
        
        return $this->_images;
    }
    
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
    
    public function getPrice()
    {
        
    }
}