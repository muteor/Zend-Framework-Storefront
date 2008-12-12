<?php
require_once dirname(__FILE__) . '/Storefront.php';

class Storefront_Catalog extends Storefront_Model
{
    public function __construct()
    {
        $this->addResource('Product', true);
        $this->addResource('Category');
    }
    
    /**
     * Get categories
     *
     * @param int $parentID The parentId
     * @return Zend_Db_Table_Rowset
     */
    public function getCategories($parentID)
    {
        $parentID = (int) $parentID;
        
        return $this->getResource('Category')->getCategories($parentID);
    }

    public function getProductById($id)
    {
        $id = (int) $id;
        
        return $this->getResource('Product')->getProductById($id);
    }
    
    public function getProductByIdent($ident)
    {        
        return $this->getResource('Product')->getProductByIdent($ident);
    }
    
    public function getProductsByCategory($categoryId, $limit=null, $order=null)
    {
        return $this->getResource('Product')->getProductsByCategory($categoryId, $limit, $order);
    }
}
