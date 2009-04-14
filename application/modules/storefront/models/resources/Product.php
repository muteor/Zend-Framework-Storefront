<?php
/** Storefront_Resource_ProductImage */
if (!class_exists('Storefront_Resource_ProductImage')) {
    require_once dirname(__FILE__) . '/ProductImage.php';
}

/** Storefront_Resource_Product_Item */
if (!class_exists('Storefront_Resource_Product_Item')) {
    require_once dirname(__FILE__) . '/Product/Item.php';
}

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
     * Get a product by its ident string
     *
     * @param string $ident The ident to search for
     * @return Storefront_Resource_Product_Item|null
     */
    public function getProductByIdent($ident)
    {
        return $this->fetchRow($this->select()->where('ident = ?', $ident));
    }
    
    /**
     * Get a list of product by their category
     *
     * @param  int|array $categoryId The category id(s)
     * @param  boolean   $paged      Use Zend_Paginator?
     * @param  array     $order      Order results
     * @return Zend_Db_Table_Rowset|Zend_Paginator
     */
    public function getProductsByCategory($categoryId, $paged=null, $order=null)
    {
        $select = $this->select();
        $select->from('product')
               ->where("categoryId IN(?)", $categoryId);
        
        if (true === is_array($order)) {
            $select->order($order);
        }
		
		if (null !== $paged) {
			$adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
			$count = clone $select;
			$count->reset(Zend_Db_Select::COLUMNS);
			$count->reset(Zend_Db_Select::FROM);
			$count->from('product', new Zend_Db_Expr('COUNT(*) AS `zend_paginator_row_count`'));
			$adapter->setRowCount($count);
			
			$paginator = new Zend_Paginator($adapter);
			$paginator->setItemCountPerPage(5)
		          	  ->setCurrentPageNumber((int) $paged);
			return $paginator;
		}
        return $this->fetchAll($select);
    } 
}