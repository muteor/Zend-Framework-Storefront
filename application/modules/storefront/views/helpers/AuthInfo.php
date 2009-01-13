<?php
/**
 * Zend_View_Helper_UserInfo
 * 
 * Access authentication data saved in the session
 * 
 * @category   Storefront
 * @package    Storefront_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
require_once 'Zend/View/Interface.php';

/**
 * AuthInfo helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_AuthInfo
{
    /**
     * @var Zend_View_Interface 
     */
    public $view;
    
    /**
     * @var Zend_Auth
     */
    protected $_auth;
    
    /**
     * Get user info from the auth session
     *
     * @param string|null $info The data to fetch, null to chain
     * @return string|Zend_View_Helper_AuthInfo
     */
    public function authInfo ($info = null)
    {
        if (null === $this->_auth) {
            $this->_auth = Zend_Auth::getInstance();    
        }
         
        if (null === $info) {
            return $this;
        }
        
        if (false === $this->isLoggedIn()) {
            return null;
        }
        
        return $this->_auth->getIdentity()->$info;
    }
    
    /**
     * Check if we are logged in
     *
     * @return boolean
     */
    public function isLoggedIn()
    {
        return $this->_auth->hasIdentity();
    }
    
    /**
     * Sets the view field 
     * @param $view Zend_View_Interface
     */
    public function setView (Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
