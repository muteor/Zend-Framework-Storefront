<?php
/**
 * @namespace SF\Controller\Helper
 */
namespace SF\Controller\Helper;

use Zend\Controller\Action\Helper;

/**
 * Simple helper to encapsulate our most common redirects
 *
 * @category   Storefront
 * @package    SF\Controller\Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class RedirectCommon extends Helper\AbstractHelper
{
    protected $_redirector;

    public function __construct()
    {
        $this->_redirector = new Helper\Redirector();
    }

    public function gotoLogin()
    {
        $this->_redirector->gotoRoute(array(
            'controller' => 'customer',
            'action' => 'login',
            ),
            'default', true
        );
    }

    public function direct($redirect)
    {
        if (method_exists($this, $redirect)) {
            return $this->$redirect();
        }
        throw new SF_Exception('Unknown common redirect method');
    }
}