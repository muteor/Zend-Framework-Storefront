<?php
/**
 * Storefront_Resource_CategoryProductMap
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Resource_CategoryProductMap extends Zend_Db_Table_Abstract 
{
    protected $_name = 'category_product';
    protected $_primary = array('productId','categoryId');
    
    protected $_referenceMap = array(
        'Products' => array(
            'columns' => 'productId',
            'refTableClass' => 'Storefront_Resource_Product',
            'refColumns' => 'productId',
        )
    );
}