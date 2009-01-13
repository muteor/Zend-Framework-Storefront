<?php
/** Storefront_Resource_User */
require_once dirname(__FILE__) . '/User.php';

/**
 * Storefront_Resource_UserAddress
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Resource_UserAddress extends Zend_Db_Table_Abstract
{
    protected $_name = 'userAddress';
    protected $_primary = 'addressId';
    
    protected $_referenceMap = array(
        'Address' => array(
            'columns' => 'userId',
            'refTableClass' => 'Storefront_Resource_User',
            'refColumns' => 'userId',
        )
    );
}