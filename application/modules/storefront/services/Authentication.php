<?php
/**
 * Storefront_Service_Authentication
 * 
 * The authentication service provides authentication services for
 * the storefront.
 * 
 * @category   Storefront
 * @package    Storefront_Service
 * @copyright  Copyright (c) 2005-2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Service_Authentication 
{
    /**
     * @var Zend_Auth_Adapter_DbTable
     */
    protected $_authAdapter;
    
    /**
     * Authenticate a user
     *
     * @param  array $credentials Matched pair array containing email/passwd
     * @return boolean
     */
    public function authenticate($credentials)
    {
        $adapter = $this->getAuthAdapter($credentials);
        $auth    = Zend_Auth::getInstance();
        $result  = $auth->authenticate($adapter);
        
        if (!$result->isValid()) {
            return false;
        }
        
        $auth->getStorage()->write($adapter->getResultRowObject(array('userId',
            'email', 'role', 'firstname', 'lastname'
        )));
        
        return true;
    }
    
    /**
     * Clear any authentication data
     */
    public function clear()
    {
        Zend_Auth::getInstance()->clearIdentity();
    }
    
    /**
     * Set the auth adpater.
     *
     * @param Zend_Auth_Adapter_Interface $adapter
     */
    public function setAuthAdapter(Zend_Auth_Adapter_Interface $adapter)
    {
        $this->_authAdapter = $adapter;
    }
    
    /**
     * Get and configure the auth adapter
     * 
     * @param  array $value Array of user credentials
     * @return Zend_Auth_Adapter_DbTable
     */
    public function getAuthAdapter($values)
    {
        if (null === $this->_authAdapter) {
            $authAdapter = new Zend_Auth_Adapter_DbTable(
                Zend_Db_Table_Abstract::getDefaultAdapter(),
                'user',
                'email',
                'passwd'
            );
            $this->setAuthAdapter($authAdapter);
        }
        $this->_authAdapter->setIdentity($values['email']);
        $this->_authAdapter->setCredential($values['passwd']);
        $this->_authAdapter->setCredentialTreatment('SHA1(CONCAT(?,salt))');
        return $this->_authAdapter;
    }
}
