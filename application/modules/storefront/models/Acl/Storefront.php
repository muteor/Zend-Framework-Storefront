<?php
namespace Storefront\Model\Acl;

use Zend\Acl,
    SF\Acl\Acl as SFAclInterface,
    Storefront\Model\Acl\Role,
    Storefront\Model\Acl\Resource
;
/**
 * Storefront_Model_Acl_Storefront
 *
 * @category   Storefront
 * @package    Storefront_Model_Acl
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront extends Acl\Acl implements SFAclInterface
{
    /**
     * Add the roles to the acl and deny all by default
     */
    public function __construct()
    {
        // Define roles:
        $this->addRole(new Role\Guest)
             ->addRole(new Role\Customer, 'Guest')
             ->addRole(new Role\Admin, 'Customer');

        // Deny privileges by default; i.e., create a whitelist
        $this->deny();

        // Add permission for non Model access restrictions
        $this->addResource(new Resource\Admin)
             ->allow('Admin');
    }
}