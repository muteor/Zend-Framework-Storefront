<?php
/**
 * Storefront_Catalog
 * 
 * @category   Storefront
 * @package    Storefront_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Model_Catalog extends SF_Model_Acl_Abstract
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

    public function getCategories()
    {
        return $this->getResource('Category')->getCategories();
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
    
    /**
     * Get a categories parents
     * 
     * @param Storefront_Resource_Category_Item $category
     * @return array
     */
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

    /**
     * Save a category
     * 
     * @param array $data
     * @param string $validator
     * @return int|false
     */
    public function saveCategory($data, $validator = null)
    {
        if (!$this->checkAcl('saveCategory')) {
            throw new SF_Acl_Exception("Insufficient rights");
        }

        if (null === $validator) {
            $validator = 'add';
        }

        $validator = $this->getForm('catalogCategory' . ucfirst($validator));

        if (!$validator->isValid($data)) {
            return false;
        }

        $data = $validator->getValues();

        return $this->getResource('Category')->saveRow($data);
    }

   /**
    * Save a product image
    * 
    * @param Storefront_Resource_Product_Item $product
    * @param array $data
    * @param string $validator
    * @return int|false
    */
   public function saveProductImage(Storefront_Resource_Product_Item $product, $data, $validator = null)
    {
        if (!$this->checkAcl('saveProductImage')) {
            throw new SF_Acl_Exception("Insufficient rights");
        }

        if (null === $validator) {
            $validator = 'add';
        }

        $validator = $this->getForm('catalogProductImage' . ucfirst($validator));

        if (!$validator->isValid($data)) {
            return false;
        }

        // get post data
        $data = $validator->getValues();

        $new = $this->getResource('Productimage')->saveRow($data);

        if ('Yes' == $data['isDefault']) {
            $this->getResource('Productimage')->setDefault($new, $product);
        }

        return $new;
    }

    public function deleteProduct($product)
    {
        if (!$this->checkAcl('deleteProduct')) {
            throw new SF_Acl_Exception("Insufficient rights");
        }

        if ($product instanceof Storefront_Resource_Product_Item_Interface) {
            $productId = (int) $product->productId;
        } else {
            $productId = (int) $product;
        }

        $product = $this->getProductById($productId);

        if (null !== $product) {
            $product->delete();
            return true;
        }

        return false;
    }

    /**
     * Implement the Zend_Acl_Resource_Interface, make this model
     * an acl resource
     *
     * @return string The resource id
     */
    public function getResourceId()
    {
        return 'Catalog';
    }

    /**
     * Injector for the acl, the acl can be injected either directly
     * via this method or by passing the 'acl' option to the models
     * construct.
     *
     * We add all the access rule for this resource here, so we
     * add $this as the resource, plus its rules.
     *
     * @param SF_Acl_Interface $acl
     * @return SF_Model_Abstract
     */
    public function setAcl(SF_Acl_Interface $acl)
    {
        if (!$acl->has($this->getResourceId())) {
            $acl->add($this)
                ->allow('Admin', $this);
        }
        $this->_acl = $acl;
        return $this;
    }

    /**
     * Get the acl and automatically instantiate the default acl if one
     * has not been injected.
     *
     * @return Zend_Acl
     */
    public function getAcl()
    {
        if (null === $this->_acl) {
            $this->setAcl(new Storefront_Model_Acl_Storefront());
        }
        return $this->_acl;
    }
}
