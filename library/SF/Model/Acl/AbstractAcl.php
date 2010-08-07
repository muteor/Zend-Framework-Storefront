<?php
/**
 * @namespace SF\Model\Acl
 */
namespace SF\Model\Acl;

use SF\Model,
    SF\Model\AbstractModel as SFAbstractModel,
    Zend\Acl\Resource as ZendAclResource,
    Zend\Acl\Role as ZendAclRole,
    Zend\Acl\Role\GenericRole as ZendAclGenericRole,
    Zend\Authentication
;

/**
 * SF_Model_Acl_Abstract
 *
 * Base model class for models that have acl support
 *
 * @category   Storefront
 * @package    SF_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
abstract class AbstractAcl extends SFAbstractModel implements Acl, ZendAclResource
{
    /**
     * @var Zend\Acl
     */
    protected $_acl;

    /**
     * @var string
     */
    protected $_identity;

    /**
     * @var Zend\Authentication\AuthenticationService
     */
    protected $_authenticationService;

    /**
     * Set the identity of the current request
     *
     * @param array|string|null|Zend_Acl_Role_Interface $identity
     * @return SF_Model_Abstract
     */
    public function setIdentity($identity)
    {
        if (is_array($identity)) {
            if (!isset($identity['role'])) {
                $identity['role'] = 'Guest';
            }
            $identity = new ZendAclGenericRole($identity['role']);
        } elseif (is_scalar($identity) && !is_bool($identity)) {
            $identity = new ZendAclGenericRole($identity);
        } elseif (null === $identity) {
            $identity = new ZendAclGenericRole('Guest');
        } elseif (!$identity instanceof ZendAclRole) {
            throw new Model\InvalidOperation('Invalid identity provided');
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
            $auth = $this->_getAuthenticationService();
            if (!$auth->hasIdentity()) {
                return 'Guest';
            }
            $this->setIdentity($auth->getIdentity());
        }
        return $this->_identity;
    }

    /**
     * Check the acl
     *
     * @param string $action
     * @return boolean
     */
    public function checkAcl($action)
    {
        return $this->getAcl()->isAllowed(
            $this->getIdentity(),
            $this,
            $action
        );
    }

    /**
     * Get the authentication service
     *
     * @return Zend\Authentication\AuthenticationService
     */
    protected function _getAuthenticationService()
    {
        if (null === $this->_authenticationService) {
            $storage = new Authentication\Storage\Session();
            $this->_authenticationService = new Authentication\AuthenticationService($storage);
        }

        return $this->_authenticationService;
    }

    /**
     * Injector for the authentication service
     * 
     * @param Authentication\AuthenticationService $auth
     */
    public function setAuthenticationService(Authentication\AuthenticationService $auth)
    {
        $this->_authenticationService = $auth;
    }
}