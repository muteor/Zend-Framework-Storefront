<?php
/**
 * Storefront_Model
 * 
 * All models within the Storefront module will extend from
 * this abstract class. This is used to cleany namespace our module.
 * 
 * @category   Storefront
 * @package    Storefront_Model
 * @copyright  Copyright (c) 2005-2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
abstract class Storefront_Model extends SF_Model_Abstract
{
    /**
     * @var Zend_Loader_PluginLoader
     */
    protected $_loader;
    
    /**
     * @var array Resource instances
     */
    protected $_instances = array();
    
    /**
     * Get plugin loader
     * 
     * @return Zend_Loader_PluginLoader
     */
    public function getPluginLoader()
    {
        if (null === $this->_loader) {
            $this->_loader = new Zend_Loader_PluginLoader();
            $this->_loader->addPrefixPath('Storefront_Resource', dirname(__FILE__) . '/resources');
        }
        return $this->_loader;
    }
    
    /**
     * Load the resource class and instantiate it.
     * 
     * @param $name The name of the resource to load
     * return SF_Model_Resource_Interface
     */
    protected function _loadResource($name) 
    {
        $name = $this->_formatName($name);
        
        if(array_key_exists($name, $this->_instances)) {
            return $this->_instances[$name];
        }
        
        $class = $this->getPluginLoader()->load($name);
        $this->_instances[$name] = new $class;
        
        return $this->_instances[$name];
    }
    
    
    /**
     * Save a row to the database
     *
     * @param array             $info The data to insert/update
     * @param string            $resourceName The resource to use
     * @param Zend_DB_Table_Row $row Optional The row to use
     * @return mixed The primary key
     * @todo Add check for Db resource
     */
    protected function _dbSave($info, $resourceName, $row = null)
    {
        if (null === $row) {
            $row = $this->getResource($resourceName)->createRow();
        }
        
        $columns = $this->getResource($resourceName)->info('cols');
        foreach ($columns as $column) {
            if (array_key_exists($column, $info)) {
                $row->$column = $info[$column];
            }
        }
        
        return $row->save();
    }
}