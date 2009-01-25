<?php
require_once 'modules/storefront/models/resources/Category/Interface.php';
require_once 'modules/storefront/models/resources/Product/Item/Interface.php';

class Test_CategoryStub extends PHPUnit_Framework_TestCase implements Storefront_Resource_Category_Interface
{
    protected $_rowset = null;
    
    function __construct ()
    {
        $data = array();
        for($i=0; $i<10; $i++) {
            $mock = $this->getMock('Storefront_Resource_Product_Item_Interface');
            
            // mock the i
            $mock->expects($this->any())
                 ->method('getImages')
                 ->will($this->returnValue(array(true)));
            
            $mock->productId = $i;
            $mock->ident = 'Product-' . $i;
            $mock->name = 'Product ' . $i;
            $mock->description = str_repeat('Product ' . $i . ' is great..', 10);
            $mock->shortDescription = 'Product ' . $i . ' is great..';
            $mock->price = ($i * 10) . '.99';
            $mock->discountPercent = rand(0,100);
            $mock->taxable = $i % 2 ? 'Yes' : 'No';
            $mock->deliveryMethod = $i % 2 ? 'Mail' : 'Download';
            $mock->stockStatus = 'InStock';
            
            $data[] = $mock;
        }

        $this->_rowset = $data;
    }
    
    public function getCategories ($parentId)
    {}
    
    public function getCategoryById ($id)
    {}
    
    public function getCategoryByIdent ($ident)
    {}
    
    public function getParentCategory (Storefront_Resource_Category_Item $category)
    {}
}
