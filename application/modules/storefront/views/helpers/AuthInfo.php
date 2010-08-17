<?php
namespace Storefront\View\Helper;

use Zend\View\Helper,
    Storefront\Service\Authentication as Auth;

/**
 * Storefront_View_Helper_UserInfo
 * 
 * Access authentication data saved in the session
 * 
 * @category   Storefront
 * @package    Storefront_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */

/**
 * AuthInfo helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class AuthInfo extends Helper\AbstractHelper
{   
    /**
     * @var Storefront_Service_Authentication
     */
    protected $_authService;

    public function direct($info = null)
    {
        return $this->_authInfo($info);
    }
    
    /**
     * Get user info from the auth session
     *
     * @param string|null $info The data to fetch, null to chain
     * @return string|Zend_View_Helper_AuthInfo
     */
    public function _authInfo ($info = null)
    {
        if (null === $this->_authService) {
            $this->_authService = new Auth();
        }
         
        if (null === $info) {
            return $this;
        }
        
        if (false === $this->isLoggedIn()) {
            return null;
        }
        
        return $this->_authService->getIdentity()->$info;
    }
    
    /**
     * Check if we are logged in
     *
     * @return boolean
     */
    public function isLoggedIn()
    {
        return $this->_authService->getAuth()->hasIdentity();
    }
}
