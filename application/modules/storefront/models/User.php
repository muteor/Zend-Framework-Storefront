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

    public function registerUser($post)
    {
        $form = $this->getForm('userRegister');
        return $this->save($form, $post, null);
    }

    public function saveUser($post)
    {
        $form = $this->getForm('userEdit');
        return $this->save($form, $post);
    }
    
    protected function save($form, $info)
    {       
        if (!$form->isValid($info)) {
            return false;
        }

        // get filtered values
        $data = $form->getValues();

        $user = array_key_exists('userId', $data) ?
            $this->getResource('User')->getUserById($data['userId']) : null;

        // password hashing
        if (array_key_exists('passwd', $data) && '' != $data['passwd']) {
            $data['salt'] = md5($this->createSalt());
            $data['passwd'] = sha1($data['passwd'] . $data['salt']);
        } else {
            unset($data['passwd']);
        }

        // default role
        if (!array_key_exists('role', $data) && null === $user && '' == $user->role) {
                $data['role'] = 'Customer';
        }
        
        return $this->getResource('User')->saveRow($data, $user);
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