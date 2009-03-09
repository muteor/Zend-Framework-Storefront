<?php
/** Storefront_Resource_User_Item */
require_once dirname(__FILE__) . '/User/Item.php';

/**
 * Storefront_Resource_User
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Resource_User extends SF_Model_Resource_Db_Table_Abstract implements Storefront_Resource_User_Interface 
{
    protected $_name = 'user';
    protected $_primary = 'userId';
    protected $_rowClass = 'Storefront_Resource_User_Item';

    public function getUserById($id)
    {
        return $this->find($id)->current();
    }
    
    public function getUserByEmail($email)
    {
        return $this->fetchRow($this->select()->where('email = ?', $email));
    }
    
    public function getUsers()
    {
        return $this->fetchAll();
    }
}