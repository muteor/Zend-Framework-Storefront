<?php
/** Storefront_Resource_Category */
require_once dirname(__FILE__) . '/Category.php';

/**
 * Storefront_Resource_CategoryImage
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2005-2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Resource_CategoryImage extends Zend_Db_Table_Abstract
{
    protected $_name = 'categoryImage';
    protected $_primary = 'imageId';
    
    protected $_referenceMap = array(
        'Image' => array(
            'columns' => 'categoryId',
            'refTableClass' => 'Storefront_Resource_Category',
            'refColumns' => 'categoryId',
        )
    );
}