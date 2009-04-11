<?php
/** Storefront_Resource_Category_Item */
if (!class_exists('Storefront_Resource_Category_Item')) {
    require_once dirname(__FILE__) . '/Category/Item.php';
}

/**
 * Storefront_Resource_Category
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Resource_Category extends SF_Model_Resource_Db_Table_Abstract implements Storefront_Resource_Category_Interface 
{
    protected $_name = 'category';
    protected $_primary = 'categoryId';
    protected $_rowClass = 'Storefront_Resource_Category_Item';
    
    protected $_referenceMap = array(
        'SubCategory' => array(
            'columns' => 'parentId',
            'refTableClass' => 'Storefront_Resource_Category',
            'refColumns' => 'categoryId',
        )
    );
    
    public function getCategoriesByParentId($parentId)
    {
        $select = $this->select()
                        ->where('parentId = ?', $parentId)
                        ->order('name');
                        
        return $this->fetchAll($select);
    }
    
    public function getCategoryByIdent($ident)
    {
        $select = $this->select()
                       ->where('ident = ?', $ident);
                       
        return $this->fetchRow($select);
    }
    
    public function getCategoryById($id)
    {
        $select = $this->select()
                       ->where('categoryId = ?', $id);
                       
        return $this->fetchRow($select);
    }

    public function getCategories()
    {
        $select = $this->select()
                       ->order('name');

        return $this->fetchAll($select);
    }
}
