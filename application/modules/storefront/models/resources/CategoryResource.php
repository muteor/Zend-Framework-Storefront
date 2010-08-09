<?php
namespace Storefront\Model\Resource;

use SF\Model\DbTable\AbstractTable as SFModelDbTableAbstract;

/**
 * Storefront_Resource_Category
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class CategoryResource extends SFModelDbTableAbstract implements Category\Resource
{
    protected $_name = 'category';
    protected $_primary = 'categoryId';
    protected $_rowClass = 'Storefront\\Model\\Resource\\Category';
    
    protected $_referenceMap = array(
        'SubCategory' => array(
            'columns' => 'parentId',
            'refTableClass' => 'Storefront\\Model\\Resource\\CategoryResource',
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
