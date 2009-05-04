<?php
/**
 * Acl action helper used for when we want to control access to resources
 * that do not have a Model.
 *
 * @category   Storefront
 * @package    SF_Controller_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Controller_Helper_Acl extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Acl
     */
    protected $_acl;

    /**
     * @var string
     */
    protected $_identity;

    /**
     * Init the acl instance for this module
     */
    public function init()
    {
        $module = $this->getRequest()->getModuleName();
        $acl = ucfirst($module) . '_Model_Acl_' . ucfirst($module);

        if (class_exists($acl)) {
            $this->_acl = new $acl;
        }


    }

    /**
     * Get the current acl
     * 
     * @return Zend_Acl 
     */
    public function getAcl()
    {
        return $this->_acl;
    }

    /**
     * Check the acl
     * 
     * @param string $resource
     * @param string $privilege
     * @return boolean 
     */
    public function isAllowed($resource=null, $privilege=null)
    {
        if (null === $this->_acl) {
            return null;
        }
        return $this->_acl->isAllowed($this->getIdentity(), $resource, $privilege);
    }


        /**
     * Set the identity of the current request
     *
     * @param array|string|null|Zend_Acl_Role_Interface $identity
     * @return SF_Controller_Helper_Acl
     */
    public function setIdentity($identity)
    {
        if (is_array($identity)) {
            if (!isset($identity['role'])) {
                $identity['role'] = 'Guest';
            }
            $identity = new Zend_Acl_Role($identity['role']);
        } elseif (is_scalar($identity) && !is_bool($identity)) {
            $identity = new Zend_Acl_Role($identity);
        } elseif (null === $identity) {
            $identity = new Zend_Acl_Role('Guest');
        } elseif (!$identity instanceof Zend_Acl_Role_Interface) {
            throw new SF_Model_Exception('Invalid identity provided');
        }
        $this->_identity = $identity;
        return $this;
    }

    /**
     * Get the identity, if no ident use guest
     *
     * @return string
     */
    public function getIdentity()
    {
        if (null === $this->_identity) {
            $auth = Zend_Auth::getInstance();
            if (!$auth->hasIdentity()) {
                return 'Guest';
            }
            $this->setIdentity($auth->getIdentity());
        }
        return $this->_identity;
    }

    /**
     * Proxy to the isAllowed method
     */
    public function direct($resource=null, $privilege=null)
    {
        return $this->isAllowed($resource, $privilege);
    }
}