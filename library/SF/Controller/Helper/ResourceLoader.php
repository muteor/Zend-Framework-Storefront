<?php
/**
 * Resource loader action helper
 * 
 * Taken from Matthews Pastebin application
 * http://github.com/weierophinney/pastebin/blob/bugapp/library/My/Helper/
 * again I can see this being included in the Zend Lib soon.
 * 
 * Commented by Keith Pope
 *
 * @uses Zend_Controller_Action_Helper_Abstract
 * @package My
 * @subpackage Controller
 * @copyright Copyright (C) 2008 - Matthew Weier O'Phinney
 * @author Matthew Weier O'Phinney <matthew@weierophinney.net>
 * @license New BSD {@link http://framework.zend.com/license/new-bsd}
 * @version $Id: $
 */
class SF_Controller_Helper_ResourceLoader extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var string The current module
     */
    protected $_currentModule;
    
    /**
     * @var array The registerd loaders
     */
    protected $_loaders = array();
 
    /**
     * Initialize resource loader
     *
     * @return void
     */
    public function __construct()
    {
    }
 
    /**
     * Proxy to resource loader methods
     *
     * @param mixed $method
     * @param mixed $args
     * @return void
     */
    public function __call($method, $args)
    {
        $loader = $this->getResourceLoader();
        return call_user_func_array(array($loader, $method), $args);
    }
 
    /**
     * Set the current Module
     *
     * @param string $module
     * @return SF_Controller_Helper_ResourceLoader
     */
    public function setCurrentModule($module)
    {
        $this->_currentModule = $module;
        return $this;
    }
 
    /**
     * Get the current module
     *
     * @return string
     */
    public function getCurrentModule()
    {
        return $this->_currentModule;
    }
 
    /**
     * Add/Overwrite resource loader 
     *
     * @param string $module
     * @param SF_Loader_Autoloader_Resource $loader
     * @return SF_Controller_Helper_ResourceLoader
     */
    public function addResourceLoader($module, $loader)
    {
        $this->_loaders[$module] = $loader;
        return $this;
    }
 
    /**
     * Check if a module has a resource loader
     *
     * @param string $module
     * @return boolean
     */
    public function hasResourceLoader($module)
    {
        return isset($this->_loaders[$module]);
    }
 
    /**
     * Get the resource loader for a module
     *
     * @param string $module
     * @return SF_Loader_Autoloader_Resource
     */
    public function getResourceLoader($module = null)
    {
        if (empty($module)) {
            $module = $this->getCurrentModule();
            if (empty($module)) {
                throw new SF_Exception("Cannot retrieve resource loader; no currently selected module");
            }
        }
        if (!$this->hasResourceLoader($module)) {
            throw new SF_Exception("No resource loader registered for $module");
        }
        return $this->_loaders[$module];
    }
 
    /**
     * Initialize paths for current module
     *
     * @return void
     */
    public function init()
    {
        if (!$this->getActionController()) {
            return;
        }
 
        $module = $this->getRequest()->getModuleName();
        $dir = $this->getFrontController()->getModuleDirectory($module);
        $this->initModule($module, $dir);
    }
 
    /**
     * Initialize prefix paths for a given module, if necessary
     *
     * @param string $module
     * @param string|null $dir
     * @return void
     */
    public function initModule($module, $dir = null)
    {
        if (null === $dir) {
            $dir = Zend_Registry::get('root') . '/modules/' . $module;
        }
 
        $module = ucfirst($module);
        if (!$this->hasResourceLoader($module)) {
            $resourceLoader = new SF_Loader_Autoloader_Resource(array(
                'prefix' => $module,
                'basePath' => $dir,
            ));
            // specialize the resource loader (We use different naming)
            $resourceLoader->clearResourceTypes();
            $resourceLoader->addResourceTypes(array(
                'Form' => array(
                    'prefix' => 'Form',
                    'path' => 'forms',
                ),
                'Model' => array(
                    'prefix' => 'Model',
                    'path' => 'models',
                ),
                'Service' => array(
                    'prefix' => 'Service',
                    'path' => 'services',
                ),
                'ModelResource' => array(
                    'prefix' => 'Resource',
                    'path'   => 'models/resources'
                ),
            ));
            $this->addResourceLoader($module, $resourceLoader);
        }
        $this->setCurrentModule($module);
    }
 
    /**
     * Proxy to a resource loader method
     *
     * @param string $resource
     * @param string $type
     * @return object
     */
    public function direct($resource, $type = 'model')
    {
        $loader = $this->getResourceLoader();
        return $loader->load($resource, $type);
    }
}