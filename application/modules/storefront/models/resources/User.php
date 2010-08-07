<?php
namespace Storefront\Model\Resource;

use SF\Model\DbTable\AbstractRow as SFModelDbRowAbstract,
    Zend\Acl\Role as ZendAclRole,
    Storefront\Model\Resource\User;

class User extends SFModelDbRowAbstract implements User\User, ZendAclRole
{
    public function getFullname()
    {
        return $this->getRow()->title . ' ' . $this->getRow()->firstname . ' ' . $this->getRow()->lastname;
    }

    public function getRoleId()
    {
        if (null === $this->getRow()->role) {
            return 'Guest';
        }
        return $this->getRow()->role;
    }
}