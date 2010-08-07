<?php
namespace Storefront\Model\Resource;

use SF\Model\DbTable\AbstractTable as SFModelDbTableAbstract,
    Storefront\Model\Resource\User;

/**
 * Storefront_Resource_User
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class UserResource extends SFModelDbTableAbstract implements User\Resource
{
    protected $_name = 'user';
    protected $_primary = 'userId';
    protected $_rowClass = 'Storefront\\Model\\Resource\\User';

    public function getUserById($id)
    {
        return $this->find($id)->current();
    }
    
    public function getUserByEmail($email, $ignoreUser=null)
    {
        $select = $this->select();
        $select->where('email = ?', $email);

        if (null !== $ignoreUser) {
            $select->where('email != ?', $ignoreUser->email);
        }

        return $this->fetchRow($select);
    }
    
    public function getUsers($paged=null, $order=null)
    {
        $select = $this->select();
        
        if (true === is_array($order)) {
            $select->order($order);
        }
        
        if (null !== $paged) {
			$adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
			$count = clone $select;
			$count->reset(Zend_Db_Select::COLUMNS);
			$count->reset(Zend_Db_Select::FROM);
			$count->from('user', new Zend_Db_Expr('COUNT(*) AS `zend_paginator_row_count`'));
			$adapter->setRowCount($count);

			$paginator = new Zend_Paginator($adapter);
			$paginator->setItemCountPerPage(5)
		          	  ->setCurrentPageNumber((int) $paged);
			return $paginator;
		}
        return $this->fetchAll($select);
    }
}