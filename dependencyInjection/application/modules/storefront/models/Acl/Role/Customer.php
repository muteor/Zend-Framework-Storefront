<?php
/**
 * Storefront_Model_Acl_Role_Customer
 *
 * @category   Storefront
 * @package    Storefront_Model_Acl
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Model_Acl_Role_Customer implements Zend_Acl_Role_Interface
{
    public function getRoleId()
    {
        return 'Customer';
    }
}