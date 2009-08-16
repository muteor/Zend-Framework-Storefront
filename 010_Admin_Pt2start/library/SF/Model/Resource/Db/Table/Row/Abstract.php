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
    /**
     * @var Zend_Db_Table_Row
     */
    protected $_row = null;

    /**
     * Constructor.
     *
     * Supported params for $config are:-
     * - rowClass = class name or object of type Zend_Db_Table_Abstract
     *
     * @param  array $config OPTIONAL Array of user-specified config options.
     * @return void
     */
    public function __construct(array $config = array())
    {
        $this->setRow($config);
    }

    /**
     * First looks for getter Methods for the columnName, that are used to lazy
     * load dependant data (data not contained in the row).
     *
     * Lastly proxies to the __get method of the row.
     *
     * @param string $columnName
     * @return mixed
     */
    public function __get($columnName)
    {
        $lazyLoader = 'get' . ucfirst($columnName);
        if (method_exists($this,$lazyLoader)) {
            return $this->$lazyLoader();
        }

        return $this->getRow()->__get($columnName);
    }

    /**
     * Checks if a row value is set, proxies to the __isset method
     * of the row
     *
     * @param string $columnName
     * @return boolean
     */
    public function __isset($columnName)
    {
        return $this->getRow()->__isset($columnName);
    }

    /**
     * Proxies to the __set method on the row
     *
     * @param string $columnName
     * @param mixed $value
     * @return mixed
     */
    public function __set($columnName, $value)
    {
        return $this->getRow()->__set($columnName, $value);
    }

    /**
     * Returns the connected row
     *
     * @return Zend_Db_Table_Abstract
     */
    public function getRow()
    {
        return $this->_row;
    }

    /**
     * Sets the row
     *
     * @param array $config
     */
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
            $this->_row = $rowClass;
            return;
        }

        throw new SF_Model_Exception('Could not set rowClass in ' . __CLASS__);
    }

    /**
     * Proxy method calls to the connected row, thing like toArray() etc
     *
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        return call_user_func_array(array($this->getRow(), $method), $arguments);
    }

    /**
     * Reconnect the table if we are serialized
     */
    public function __wakeup()
    {
        if (!$this->getRow()->isConnected()) {
            $tableClass = $this->getRow()->getTableClass();
            $table = new $tableClass();
            $this->getRow()->setTable($table);
        }
    }
}
