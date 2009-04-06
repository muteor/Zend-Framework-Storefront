<?php
/** Storefront_Resource_ProductImage_Item */
if (!class_exists('Storefront_Resource_ProductImage_Item')) {
    require_once dirname(__FILE__) . '/ProductImage/Item.php';
}

/** Storefront_Resource_Product */
if (!class_exists('Storefront_Resource_Product')) {
    require_once dirname(__FILE__) . '/Product.php';
}

/**
 * Storefront_Resource_ProductImage
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Resource_ProductImage extends SF_Model_Resource_Db_Table_Abstract implements Storefront_Resource_ProductImage_Interface 
{
    protected $_name = 'productImage';
    protected $_primary = 'imageId';
    protected $_rowClass = 'Storefront_Resource_ProductImage_Item';

    protected $_referenceMap = array(
        'Image' => array(
            'columns' => 'productId',
            'refTableClass' => 'Storefront_Resource_Product',
            'refColumns' => 'productId',
        )
    );
}