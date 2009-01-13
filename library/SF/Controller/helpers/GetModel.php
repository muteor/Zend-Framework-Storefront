<?php
/**
 * SF_Helper_GetModel
 * 
 * Action helper to locate and load models
 * 
 * @category   Storefront
 * @package    Storefront_Action_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Helper_GetModel extends Zend_Controller_Action_Helper_Abstract
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
            'Storefront' => Zend_Registry::get('root') . '/application/modules/' . $module . '/models'
        ));
    }

    /**
     * Load a model class and return an object instance
     * 
     * @param  string $model 
     * @return SF_Model_Abstract
     */
    public function getModel($model)
    {        
        $class = $this->_loader->load($model);
        return new $class;
    }

    /**
     * Proxy to getModel()
     * 
     * @param  string $model 
     * @return SF_Model_Abstract
     */
    public function direct($model)
    {
        return $this->getModel($model);
    }
    
}