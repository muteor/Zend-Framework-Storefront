<?php
/**
 * SF_Helper_GetService
 * 
 * Action helper to locate and load services
 * 
 * @category   Storefront
 * @package    Storefront_Action_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Helper_GetService extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Loader_PluginLoader
     */
    protected $_loader;

    /**
     * Initialize plugin loader for models
     * 
     * @return void
     */
    public function __construct()
    {
        $front = Zend_Controller_Front::getInstance();
        $module = $front->getRequest()->getModuleName();
        
        $this->_loader = new Zend_Loader_PluginLoader(array(
            'Storefront_Service' => Zend_Registry::get('root') . '/application/modules/' . $module . '/services'
        ));
    }

    /**
     * Load a service class and return an object instance
     * 
     * @param  string $service
     * @param  string $module string|null
     * @return object
     */
    public function getService($service,$module=null)
    {
        if (null !== $module) {
            $this->_loader->addPrefixPath(
                ucfirst($module), 
                Zend_Registry::get('root') . '/application/modules/' . $module . '/services'
            );
        }
        
        $class = $this->_loader->load($service);
        return new $class;
    }

    /**
     * Proxy to getService()
     * 
     * @param  string $service
     * @param  string $module string|null 
     * @return object
     */
    public function direct($service,$module=null)
    {
        return $this->getService($service);
    }
    
}