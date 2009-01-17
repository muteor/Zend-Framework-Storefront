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
    
    public function getCategoryIdent($ident)
    {
        return $this->getResource('Category')->getCategoryByIdent($ident);
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
