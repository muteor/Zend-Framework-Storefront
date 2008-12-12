<?php
/** Storefront_Resource_Category_Interface */
require_once dirname(__FILE__) . '/Category/Interface.php';

/**
 * Storefront_Resource_Category
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2005-2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Resource_Category extends Zend_Db_Table_Abstract implements Storefront_Resource_Category_Interface 
{
    protected $_name = 'category';
    protected $_primary = 'categoryId';
    
    protected $_referenceMap = array(
        'SubCategory' => array(
            'columns' => 'parentId',
            'refTableClass' => 'Storefront_Resource_Category',
            'refColumns' => 'categoryId',
        )
    );
    
    public function getCategories($parentId)
    {
        $select = $this->select()
                        ->where('parentId = ?', $parentId)
                        ->order('name');
                        
        return $this->fetchAll($select);
    }
}
