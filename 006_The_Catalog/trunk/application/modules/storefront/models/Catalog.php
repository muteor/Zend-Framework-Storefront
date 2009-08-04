<?php
/**
 * Storefront_Catalog
 * 
 * @category   Storefront
 * @package    Storefront_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Model_Catalog extends SF_Model_Abstract
{    
    /**
     * Get categories
     *
     * @param int $parentID The parentId
     * @return Zend_Db_Table_Rowset
     */
    public function getCategoriesByParentId($parentID)
    {
        $parentID = (int) $parentID;
        
        return $this->getResource('Category')->getCategoriesByParentId($parentID);
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
        $categories = $this->getCategoriesByParentId($categoryId);
        $cats = array();
               
        foreach ($categories as $category) {
            $cats[] = $category->categoryId;
            
            if (true === $recursive) {
                $cats = array_merge($cats, $this->getCategoryChildrenIds($category->categoryId, true));
            }
        }

        return $cats;
    }
    
    public function getParentCategories($category, $appendParent = true)
    {
        $cats = $appendParent ? array($category) : array();

        if (0 == $category->parentId) {
            return $cats;
        }

        $parent = $category->getParentCategory();
        $cats[] = $parent;

        if (0 != $parent->parentId) {
            $cats = array_merge($cats, $this->getParentCategories($parent, false));
        }

        return $cats;
    }
}
