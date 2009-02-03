<?php
/** Storefront_Model */
require_once dirname(__FILE__) . '/Storefront.php';

/**
 * Storefront_Catalog
 * 
 * @category   Storefront
 * @package    Storefront_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Model_Catalog extends Storefront_Model
{    
    public function getCategoriesByParentId($parentID)
    {}
    
    public function getCategoryByIdent($ident)
    {}

    public function getProductById($id)
    {}

    public function getProductByIdent($ident)
    {}

    public function getProductsByCategory($category, $paged=false, $order=null, $deep=true)
    {}

    public function getCategoryChildrenIds($categoryId, $recursive = false)
    {}
    
    public function getParentCategories($category)
    {}
    
    public function getParentCategory(Storefront_Resource_Category_Item_Interface $category)
    {}
}
