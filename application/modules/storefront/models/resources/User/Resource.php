<?php
namespace Storefront\Model\Resource\User;

use SF\Model\Db as DbInterface;

/**
 * Storefront\Resource\User\Resource
 *
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface Resource extends DbInterface
{
    public function getUserById($id);
    public function getUserByEmail($email, $ignoreUser=null);
    public function getUsers($paged=false, $order=null);
}
