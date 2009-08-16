<?php
/**
 * Storefront_Resource_ProductImage_Item_Interface
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface Storefront_Resource_ProductImage_Item_Interface
{
    public function getThumbnail();
    public function getFull();
    public function isDefault();
}