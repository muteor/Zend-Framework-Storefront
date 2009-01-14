<?php
/** Storefront_Resource_ProductImage */
//require_once dirname(__FILE__) . '/ProductImage.php';

/** Storefront_Resource_Product_Interface */
require_once dirname(__FILE__) . '/Product/Interface.php';

/** Storefront_Resource_Product_Interface */
require_once dirname(__FILE__) . '/Product/Item.php';

require_once dirname(__FILE__) . '/CategoryProductMap.php';

/**
 * Storefront_Resource_Product
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Resource_Product extends SF_Model_Resource_Db_Table_Abstract implements Storefront_Resource_Product_Interface 
{
    protected $_name    = 'product';
    protected $_primary  = 'productId';
    protected $_rowClass = 'Storefront_Resource_Product_Item';
    
    /**
     * Get a product by its productId
     *
     * @param int $id The id to search for
     * @return Storefront_Resource_Product_Item|null
     */
    public function getProductById($id)
    {
        return $this->find($id)->current();
    }
    
    /**
     * Enter description here...
     *
     * @param unknown_type $ident
     * @return unknown
     */
    public function getProductByIdent($ident)
    {
        return $this->fetchRow($this->select()->where('ident = ?', $ident));
    }
    
    /**
     * Enter description here...
     *
     * @param unknown_type $categoryId
     * @param unknown_type $limit
     * @param unknown_type $order
     * @return unknown
     */
    public function getProductsByCategory($categoryId, $limit=null, $order=null)
    {
        $select = $this->select()->setIntegrityCheck(true);
        $select->from('product')
               ->where("categoryId IN(?)", $categoryId);
       
        if (true === is_array($limit)) {
            $offset = isset($limit[1]) ? $limit[1] : 0;
            $limit  = $limit[0];
            $select->limit((int) $limit, (int) $offset);
        }
        
        if (true === is_array($order)) {
            $select->order($order);
        }
               
        return $this->fetchAll($select, array('catIds' => $categoryId));
    } 
    
    public function saveProduct($info)
    {}
}