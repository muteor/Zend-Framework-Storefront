<?php
class Storefront_Model_User extends SF_Model_Abstract
{   
    public function getUserById($id)
    {
        $id = (int) $id;
        return $this->getResource('User')->getUserById($id);
    }

    public function getUserByEmail($email, $ignoreUser=null)
    {
        return $this->getResource('User')->getUserByEmail($email, $ignoreUser);
    }
    
    public function getUsers($paged=false, $order=null)
    {
        return $this->getResource('User')->getUsers($paged, $order);
    }
    
    public function saveUser($info)
    {
        $user = array_key_exists('userId', $info) ?
            $this->getResource('User')->getUserById($info['userId']) : null;

        $form = $this->getForm('register');
        if (null !== $user) {
            $form = $this->getForm('editUser');
        }
        
        if (!$form->isValid($info)) {
            return false;
        }

        // password hashing
        if (array_key_exists('passwd', $info) && '' != $info['passwd']) {
            $info['salt'] = md5($this->createSalt());
            $info['passwd'] = sha1($info['passwd'] . $info['salt']);
        }
        // default role
        if (!array_key_exists('role', $info)) {
            $info['role'] = 'Customer';
        }      
        
        return $this->getResource('User')->saveRow($info, $user);        
    }
    
    private function createSalt()
    {
        $salt = '';
        for ($i = 0; $i < 50; $i++) {
            $salt .= chr(rand(33, 126));
        }
        return $salt;
    }
}