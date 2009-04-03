<?php
/**
 * SF_Model_Resource_Db_Table_Abstract
 * 
 * Provides some common db functionality that is shared
 * across our db-based resources.
 * 
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
abstract class SF_Model_Resource_Db_Table_Abstract extends Zend_Db_Table_Abstract implements SF_Model_Resource_Db_Interface
{
	/**
     * Save a row to the database
     *
     * @param array             $info The data to insert/update
     * @param Zend_DB_Table_Row $row Optional The row to use
     * @return mixed The primary key
     */
    public function saveRow($info, $row = null)
    {
        if (null === $row) {
            $row = $this->createRow();
        }
        
        $columns = $this->info('cols');
        foreach ($columns as $column) {
            if (array_key_exists($column, $info)) {
                $row->$column = $info[$column];
            }
        }
        
        return $row->save();
    }
}
