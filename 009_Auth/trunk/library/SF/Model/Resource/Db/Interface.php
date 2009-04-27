<?php
/**
 * SF_Model_Resource_Db_Interface
 * 
 * Provides some Zend db specific methods for our resources
 * that use Zend_Db_Table. This will hopefully protect us from change
 * in the future.
 */
interface SF_Model_Resource_Db_Interface extends SF_Model_Resource_Interface
{
	/** ZF methods */
    public function info($key = null);
    public function createRow(array $data = array());
	
	/** SF methods */
	public function saveRow($info, $row = null);
}