<?php
/**
 * Storefront_Model_Acl_Resource_Admin
 *
 * @category   Storefront
 * @package    Storefront_Model_Acl
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Model_Acl_Resource_Admin implements Zend_Acl_Resource_Interface
{
    public function getResourceId()
    {
        return 'Admin';
    }
}