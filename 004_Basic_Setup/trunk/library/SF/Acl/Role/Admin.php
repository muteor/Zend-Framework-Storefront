<?php
/**
 * SF_Acl_Role_Admin
 * 
 * @category   Storefront
 * @package    Storefront_Acl_Role
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Acl_Role_Admin implements Zend_Acl_Role_Interface
{
    public function getRoleId()
    {
        return 'admin';
    }
}