<?php
/**
 * SF_Model_Resource_Db_Table_Row_Abstract
 * 
 * Composite the Zend_Db_Table_Row
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
abstract class SF_Model_Resource_Db_Table_Row_Abstract
{
    protected $_row = null;

    public function __construct(array $config = array()) 
    {
        $this->setRow($config);
    }
    
    public function __get($columnName)
    {
        $lazyLoader = 'get' . ucfirst($columnName);
        if (method_exists($this,$lazyLoader)) {
            return $this->$lazyLoader();
        }
        
        return $this->getRow()->__get($columnName);
    }

    public function __isset($columnName)
    {
        return $this->getRow()->__isset($columnName);
    }

    public function __set($columnName, $value)
    {
        return $this->getRow()->__set($columnName, $value);
    }
    
    public function getRow()
    {
        return $this->_row;
    }
    
    public function setRow(array $config = array())
    {
        $rowClass = 'Zend_Db_Table_Row';  
        if (isset($config['rowClass'])) {
            $rowClass = $config['rowClass'];
        }
        
        if (is_string($rowClass)) {
            $this->_row = new $rowClass($config);
            return;
        }
        
        if (is_object($rowClass)) {
            $this->_row = new $rowClass($config);
            return;
        }
        
        throw new SF_Model_Exception('Could not set rowClass in ' . __CLASS__);
    }
    
    public function __call($method, array $arguments)
    {       
        return call_user_func_array(array($this->getRow(), $method), $arguments);
    }
}
