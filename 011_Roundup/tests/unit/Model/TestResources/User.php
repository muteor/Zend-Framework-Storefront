<?php
require_once 'modules/storefront/models/resources/User/Interface.php';
require_once 'modules/storefront/models/resources/User/Item/Interface.php';

class Storefront_Resource_User extends PHPUnit_Framework_TestCase implements Storefront_Resource_User_Interface
{
    protected $_rowset = null;
    
    public function __construct()
    {
        $data = array();
        for($i=0; $i<10; $i++) {
            $mock = $this->getMock('Storefront_Resource_User_Item_Interface');
            
            $mock->userId = $i;
            $mock->title = $i % 2 ? 'Mr' : 'Mrs';
            $mock->firstname = 'John' . $i;
            $mock->lastname = 'Doe' . $i;
            $mock->email = 'jd' . $i . '@noemail.com';
            $mock->passwd = sha1(1234561);
            $mock->salt = md5(1);
            $mock->role = $i % 2 ? 'Customer' : 'Admin';
            
            $data[] = $mock;
        }
        $this->_rowset = $data;
    }
    
    public function getUserById($id)
    {
        foreach ($this->_rowset as $user) {
            if($id === $user->userId) {
                return $user;
            }
        }
        return null;
    }
    
    public function getUserByEmail($email, $ignoreUser=null)
    {
        foreach ($this->_rowset as $user) {
            if($email === $user->email) {
                return $user;
            }
        }
        return null;
    }
    
    public function getUsers($paged=false, $order=null)
    {}
    
    public function info($key = null)
    {
        if($key == 'cols') {
            return array('userId','title','firstname','lastname','email','passwd','salt','role');
        }
    }
    
    public function createRow(array $data = array(), $defaultSource = null)
    {
        $mock = $this->getMock('Storefront_Resource_User_Item_Interface', array('getFullname', 'save'));
        $mock->expects($this->any())
             ->method('save')
             ->will($this->returnValue(10));
       
        $this->_rowset[10] = $mock;
        
        return $mock;
    }
	
	public function saveRow($info, $row = null)
	{
		if (null === $row) {
            $row = $this->createRow();
        }

        $columns = $this->info('cols');
        foreach ($columns as $column) {
            if (array_key_exists($column, $info)) {
                $row->$column = $info[$column];
            }
        }

        return $row->save();
	}
}