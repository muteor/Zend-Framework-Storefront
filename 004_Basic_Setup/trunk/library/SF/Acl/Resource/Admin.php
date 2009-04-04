<?php
/**
 * SF_Acl_Resource_Admin
 * 
 * @category   Storefront
 * @package    Storefront_Acl_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Acl_Resource_Admin implements Zend_Acl_Resource_Interface
{
    public function getResourceId()
    {
        return 'admin';
    }
}