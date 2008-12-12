<?php
require_once dirname(__FILE__) . '/Item/Interface.php';

class Storefront_Resource_Product_Item extends Zend_Db_Table_Row_Abstract implements Storefront_Resource_Product_Item_Interface
{
    public function getImages()
    {
        $this->findDependentRowset('Bugs');
    }
    public function getDefaultImage()
    {}
}