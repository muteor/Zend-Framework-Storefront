<?php
/**
 * SF_Application_Resource_Yadif
 *
 * Class Resource for Zend_Application, loads the dependency injection objects
 * into the container.
 *
 * Requires Yadif to be registered as the container in Zend_Application.
 *
 * @category   Storefront
 * @package    SF_Application_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Application_Resource_Yadif extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * @var Yadif_Container
     */
    protected $_yadif;

    /**
     * Concrete init @see Zend_Application_Resource_ResourceAbstract
     */
    public function init()
    {
        $l = Zend_Loader_Autoloader::getInstance();
        //print_r($l);
        //die();
        $this->_yadif = $this->getBootstrap()->getContainer();

        if (!$this->_yadif instanceof Yadif_Container) {
            throw new Max_Application_Exception('The container must be an instance of Yadif_Container');
        }

        $this->_loadObjects();
    }

    /**
     * Load the objects for each module
     */
    protected function _loadObjects()
    {
        $bootstrap = $this->getBootstrap();
        $bootstrap->bootstrap('FrontController');
        $front = $bootstrap->getResource('FrontController');

        $modules = $front->getControllerDirectory();
        $default = $front->getDefaultModule();
        foreach ($modules as $module => $moduleDirectory) {
            $objectsClass = $this->_formatModuleName($module) . '_Objects';
            if (!class_exists($objectsClass, false)) {
                $objectsPath  = dirname($moduleDirectory) . '/Objects.php';
                if (file_exists($objectsPath)) {
                    $eMsgTpl = 'Objects file found for module "%s" but objects class "%s" not found';
                    include_once $objectsPath;
                    if (!class_exists($objectsClass, false)) {
                        throw new Zend_Application_Resource_Exception(sprintf(
                            $eMsgTpl, $module, $objectsClass
                        ));
                    }
                    $objects = new $objectsClass;
                    $this->_yadif->addComponents($objects->getConfig());
                }
            }
        }
    }

    /**
     * Format a module name to the module class prefix
     *
     * @param  string $name
     * @return string
     */
    protected function _formatModuleName($name)
    {
        $name = strtolower($name);
        $name = str_replace(array('-', '.'), ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        return $name;
    }
}