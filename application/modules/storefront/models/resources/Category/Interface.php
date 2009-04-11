<?php
/**
 * Storefront_Resource_Category_Interface
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface Storefront_Resource_Category_Interface 
{
    public function getCategoriesByParentId($parentId);
    public function getCategoryByIdent($ident);
    public function getCategoryById($id);
    public function getCategories();
}