<?php
require_once dirname(__FILE__) . '/Item/Interface.php';

class Storefront_Resource_Category_Item extends SF_Model_Resource_Db_Table_Row_Abstract implements Storefront_Resource_Category_Item_Interface
{
    public function hasChildren()
    {
    }
}