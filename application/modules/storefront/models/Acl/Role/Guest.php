<?php
namespace Storefront\Model\Acl\Role;

use Zend\Acl\Role as ZendAclRole;
/**
 * Guest
 *
 * @category   Storefront
 * @package    Storefront_Model_Acl
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Guest implements ZendAclRole
{
    public function getRoleId()
    {
        return 'Guest';
    }
}