<?php
namespace Storefront\Model\Acl\Role;

use Zend\Acl\Role as ZendAclRole;
/**
 * Admin
 *
 * @category   Storefront
 * @package    Storefront_Model_Acl
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Admin implements ZendAclRole
{
    public function getRoleId()
    {
        return 'Admin';
    }
}