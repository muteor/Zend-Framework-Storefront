<?php
namespace Storefront\Model\Resource\Category;

/**
 * Storefront_Resource_Product_Item_Interface
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface Category
{
    public function getParentCategory();
}