<?php
/**
 * Storefront_Model_User
 *
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Model_User extends SF_Model_Acl_Abstract
{    
    /**
     * Get User by their id
     * 
     * @param  int $id
     * @return null|Storefront_Resource_User_Item 
     */
    public function getUserById($id)
    {
        $id = (int) $id;
        return $this->getResource('User')->getUserById($id);
    }

    /**
     * Get User by their email address
     *
     * @param  string $email The email address to search for
     * @param  Storefront_Resource_User_Item $ignoreUser User to ignore from the search
     * @return null|Storefront_Resource_User_Item 
     */
    public function getUserByEmail($email, $ignoreUser=null)
    {
        return $this->getResource('User')->getUserByEmail($email, $ignoreUser);
    }
    
    /**
     * Get all Users
     * 
     * @param  boolean $paged Return paginator?
     * @param  array   $order The order fields
     * @return Zend_Db_Table_Rowset
     */
    public function getUsers($paged=false, $order=null)
    {
        return $this->getResource('User')->getUsers($paged, $order);
    }

    /**
     * Register a new user
     * 
     * @param array $post
     * @return false|int 
     */
    public function registerUser($post)
    {
        if (!$this->checkAcl('register')) {
            throw new SF_Acl_Exception("Insufficient rights");
        }
        
        $form = $this->getForm('userRegister');
        return $this->save($form, $post, array('role' => 'Customer'));
    }

    /**
     * Update a user
     * 
     * @param  array  $post The data
     * @param  string $validator Which validation chain to use
     * @return false|int
     */
    public function saveUser($post, $validator = null)
    {
        if (!$this->checkAcl('saveUser')) {
            throw new SF_Acl_Exception("Insufficient rights");
        }

        if (null === $validator) {
            $validator = 'edit';
        }

        $form = $this->getForm('user' . ucfirst($validator));

        return $this->save($form, $post);
    }
    
    /**
     * Save the data to db
     *
     * @param  Zend_Form $form The Validator
     * @param  array     $info The data
     * @param  array     $defaults Default values
     * @return false|int 
     */
    protected function save($form, $info, $defaults=array())
    {       
        if (!$form->isValid($info)) {
            return false;
        }

        // get filtered values
        $data = $form->getValues();

        // password hashing
        if (array_key_exists('passwd', $data) && '' != $data['passwd']) {
            $data['salt'] = md5($this->createSalt());
            $data['passwd'] = sha1($data['passwd'] . $data['salt']);
        } else {
            unset($data['passwd']);
        }

        // apply any defaults
        foreach ($defaults as $col => $value) {
            $data[$col] = $value;
        }

        $user = array_key_exists('userId', $data) ?
            $this->getResource('User')->getUserById($data['userId']) : null;

        return $this->getResource('User')->saveRow($data, $user);
    }

    /**
     * Delete a user
     *
     * @param int|Storefront_Resource_User_Item_Interface $user
     * @return boolean
     */
    public function deleteUser($user)
    {
        if (!$this->checkAcl('deleteUser')) {
            throw new SF_Acl_Exception("Insufficient rights");
        }

        if ($user instanceof Storefront_Resource_User_Item_Interface) {
            $userId = (int) $user->userId;
        } else {
            $userId = (int) $user;
        }
        
        $user = $this->getUserById($userId);

        if (null !== $user) {
            $user->delete();
            return true;
        }

        return false;
    }

    /**
     * Implement the Zend_Acl_Resource_Interface, make this model
     * an acl resource
     * 
     * @return string The resource id 
     */
    public function getResourceId()
    {
        return 'User';
    }

    /**
     * Injector for the acl, the acl can be injected either directly
     * via this method or by passing the 'acl' option to the models
     * construct.
     *
     * We add all the access rule for this resource here, so we
     * add $this as the resource, plus its rules.
     * 
     * @param SF_Acl_Interface $acl
     * @return SF_Model_Abstract
     */
    public function setAcl(SF_Acl_Interface $acl)
    {
        if (!$acl->has($this->getResourceId())) {
            $acl->add($this)
                ->allow('Guest', $this, array('register'))
                ->allow('Customer', $this, array('saveUser'))
                ->allow('Admin', $this);
        }
        $this->_acl = $acl;
        return $this;
    }

    /**
     * Get the acl and automatically instantiate the default acl if one
     * has not been injected.
     * 
     * @return Zend_Acl
     */
    public function getAcl()
    {
        if (null === $this->_acl) {
            $this->setAcl(new Storefront_Model_Acl_Storefront());
        }
        return $this->_acl;
    }
    
    /**
     * Create the salt string
     * 
     * @return string 
     */
    private function createSalt()
    {
        $salt = '';
        for ($i = 0; $i < 50; $i++) {
            $salt .= chr(rand(33, 126));
        }
        return $salt;
    }
}