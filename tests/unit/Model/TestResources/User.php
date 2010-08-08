<?php
namespace SFTest\Model\Resource;

use Storefront\Model\Resource\User,
    Mockery as m;

class UserResource implements User\Resource
{
    protected $_rowset = null;
    
    public function __construct()
    {
        $data = array();
        for($i=0; $i<10; $i++) {
            $mock = m::mock('Storefront\\Model\\Resource\\User\\User');
            
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
        $mock = m::mock('Storefront\\Model\\Resource\\User\\User');
        $mock->shouldReceive('save')->andReturn(10);
       
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