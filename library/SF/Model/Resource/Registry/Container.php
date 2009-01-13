<?php
/**
 * SF_Model_Resource_Registry_Container
 * 
 * Simple value object that hold resource information 
 * in the registry.
 * 
 * @category   Storefront
 * @package    SF_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License 
 */
class SF_Model_Resource_Registry_Container
{
    public $identifier;
    public $className;
    public $isDefault;
    public $locked;
    
    /**
     * Constructor
     *
     * @param string  $identifier The identifer for this container
     * @param string  $className  The class to use
     * @param boolean $isDefault  Optional Is this default resource?
     * @param boolean $lock       Optional Can this be overwritten?
     */
    public function __construct($identifier, $className, $isDefault=false, $lock=false)
    {
        $this->identifier = $identifier;
        $this->className  = $className;
        $this->isDefault  = $isDefault;
        $this->locked     = $lock;
    }
    
    /**
     * Is this default?
     *
     * @return boolean
     */
    public function isDefault()
    {
        return $this->isDefault;
    }
    
    /**
     * Is this locked?
     *
     * @return boolean
     */
    public function isLocked()
    {
        return $this->locked;
    }
    
    /**
     * Check if this container belongs to a specific
     * namespace. This is used to so containers can belong
     * to specific classes, the namespace is usually the 
     * owning classes name.
     *
     * @param string $namespace Namespace to test for
     * @return boolean
     */
    public function inNamespace($namespace)
    {
        return preg_match("/^$namespace/", $namespace) == true ? true : false;
    }
}