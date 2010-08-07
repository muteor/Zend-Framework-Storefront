<?php
namespace Storefront\Model\Acl\Role;

use Zend\Acl\Role as ZendAclRole;
/**
 * Customer
 *
 * @category   Storefront
 * @package    Storefront_Model_Acl
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Customer implements ZendAclRole
{
    public function getRoleId()
    {
        return 'Customer';
    }
}