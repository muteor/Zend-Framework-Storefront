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
    /**
     * Construct
     * 
     * Add required resources
     */
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
    
    /**
     * Get category by ident
     *
     * @param string $ident The ident string
     * @return Storefront_Resource_Category_Item|null
     */
    public function getCategoryByIdent($ident)
    {
        return $this->getResource('Category')->getCategoryByIdent($ident);
    }

    /**
     * Get a product by its id
     *
     * @param  int $id The id
     * @return Storefront_Resource_Product_Item
     */
    public function getProductById($id)
    {
        $id = (int) $id;
        
        return $this->getResource('Product')->getProductById($id);
    }
    
    /**
     * Get a product by its ident
     *
     * @param  string $ident The ident
     * @return Storefront_Resource_Product_Item
     */
    public function getProductByIdent($ident)
    {        
        return $this->getResource('Product')->getProductByIdent($ident);
    }
    
    /**
     * Get products in a category
     *
     * @param int|string  $category The category name or id
     * @param int|boolean $paged    Whether to page results
     * @param array       $order    Order results
     * @param boolean     $deep     Get all products below this category?
     * @return Zend_Db_Table_Rowset|Zend_Paginator|null
     */
    public function getProductsByCategory($category, $paged=false, $order=null, $deep=true)
    {
        if (is_string($category)) {
            $cat = $this->getResource('Category')->getCategoryByIdent($category);
            $categoryId = null === $cat ? 0 : $cat->categoryId;
        } else {
            $categoryId = $category;
        }
        
        if (true === $deep) {
            $ids = $this->getCategoryChildrenIds($categoryId, true);
            $ids[] = $categoryId;
            $categoryId = null === $ids ? $categoryId : $ids;
        }
        
        return $this->getResource('Product')->getProductsByCategory($categoryId, $paged, $order);
    }
    
    /**
     * Get a categories children categoryId values
     *
     * @param int     $categoryId The category to get children from
     * @param boolean $recursive  Get the entire category branch?
     * @return array An array of ids
     */
    public function getCategoryChildrenIds($categoryId, $recursive = false)
    {
        $categories = $this->getCategories($categoryId);
        $cats = array();
               
        foreach ($categories as $category) {
            $cats[] = $category->categoryId;
            
            if (true === $recursive) {
                $cats = array_merge($cats, $this->getCategoryChildrenIds($category->categoryId, true));
            }
        }

        return $cats;
    }
}
