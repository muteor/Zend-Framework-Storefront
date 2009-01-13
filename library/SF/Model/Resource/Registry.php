<?php
/**
 * SF_Model_Resource_Registry
 *
 * Provides a registry for model resources so we can replace
 * resources for testing and avoid requiring a database connection.
 * 
 * @category   Storefront
 * @package    SF_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Model_Resource_Registry
{
    /**
     * Registry namespace so we dont affect Zend_Registry
     */
    const REG_NS = 'SFR_';
    
    /**
     * Get a container
     * 
     * @param string $index The index to get.
     * @return SF_Model_Resource_Registry_Container
     */
    public static function get($index)
    {
        return Zend_Registry::get(self::REG_NS . $index);
    }
    
    /**
     * Set a container
     * 
     * @param  string  $index The index to register under
     * @param  SF_Model_Resource_Registry_Container $value
     * @return boolean
     */
    public static function set($index, SF_Model_Resource_Registry_Container $value)
    {
        Zend_Registry::set(self::REG_NS . $index, $value);
    }
    
    /**
     * Check if a container is registered
     * 
     * @param  string  $index The index to test for
     * @return boolean
     */
    public static function isRegistered($index)
    {
        return Zend_Registry::isRegistered(self::REG_NS . $index);
    }
    
    /**
     * Get Zend_Registry
     * 
     * @return Zend_Registry
     */
    public static function getInstance()
    {
        return Zend_Registry::getInstance();
    }
    
    /**
     * Used for testing only, clears all registered containers
     * 
     * @return void
     */
    public static function clear()
    {
        $registry = Zend_Registry::getInstance();
        $matches = array();
        
        foreach ($registry as $index => $value) {
            if ($value instanceof SF_Model_Resource_Registry_Container) {
                $matches[] = $index;
            }
        }
        
        foreach ($matches as $match) {
            $registry->offsetUnset($match);
        }
    }
}