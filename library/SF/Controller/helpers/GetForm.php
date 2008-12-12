<?php
/**
 * SF_Helper_GetForm
 * 
 * Action helper to locate and load forms
 * 
 * @category   Storefront
 * @package    Storefront_Action_Helper
 * @copyright  Copyright (c) 2005-2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Helper_GetForm extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var array Form instances
     */
    protected $_forms = array();

    /**
     * @var Zend_Loader_PluginLoader
     */
    protected $_loader;

    /**
     * Initialize plugin loader for forms
     * 
     * @return void
     */
    public function __construct()
    {
        $front = Zend_Controller_Front::getInstance();
        $module = $front->getRequest()->getModuleName();
        
        $this->_loader = new Zend_Loader_PluginLoader(array(
            'Storefront_Form' => Zend_Registry::get('root') . '/application/modules/' . $module . '/forms'
        ));
    }

    /**
     * Load and return a form object
     * 
     * @param  string $form 
     * @param  Zend_Config|array $config 
     * @param  SF_Model_Abstract|null
     * @return Zend_Form
     */
    public function getForm($form, $config = null, $model = null)
    {
        if (!array_key_exists($form, $this->_forms)) {
            $class = $this->_loader->load($form);
            $this->_forms[$form] = new $class($config, $model);
        }
        return $this->_forms[$form];
    }

    /**
     * Proxy to getForm()
     * 
     * @param  string $form 
     * @param  array|Zend_Config|null $config 
     * @param  SF_Model_Abstract|null
     * @return Zend_Form
     */
    public function direct($form, $config = null, $model = null)
    {
        return $this->getForm($form, $config, $model);
    }
}

