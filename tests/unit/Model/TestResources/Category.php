<?php
require_once 'modules/storefront/models/resources/Category/Interface.php';
require_once 'modules/storefront/models/resources/Category/Item/Interface.php';
require_once 'modules/storefront/models/resources/Product/Item/Interface.php';

class Storefront_Resource_Category extends PHPUnit_Framework_TestCase implements Storefront_Resource_Category_Interface
{
    protected $_products = null;
    protected $_categories = null;
    
    function __construct ()
    {
        // create some test products
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
        
        $this->_products = $data;
        
        $data = array();
        for($i=0; $i<10; $i++) {
            $mock = $this->getMock('Storefront_Resource_Category_Item_Interface');
            
            $mock->categoryId = $i;
            $mock->name = 'Category ' . $i;
            $mock->parentId = $i > 5 ? ($i-1) : 0; 
            $mock->ident = 'Category-' . $i;

            if($i > 0) {
            $mock->expects($this->any())
                      ->method('getParentCategory')
                      ->will($this->returnValue($data[$i-1]));
            }
            $data[] = $mock;
        }
        
        $this->_categories = $data;
    }
    
    public function getCategoriesByParentId($parentId)
    {
        $this->assertType('int', $parentId, 'Assertion failed in ' . __CLASS__ . ' ' . __METHOD__);
        
        $found = array();
        foreach ($this->_categories as $cat) {
            if ($parentId === $cat->parentId) {
                $found[] = $cat;
            }
        }
        return $found;
    }
    
    public function getCategoryById ($id)
    {}

    public function getCategories()
    {}
    
    public function getCategoryByIdent ($ident)
    {
        $this->assertType('string', $ident, 'Assertion failed in ' . __CLASS__ . ' ' . __METHOD__);
        
        foreach ($this->_categories as $cat) {
            if ($ident === $cat->ident) {
               return $cat;
            }
        }
        return null;
    }
}
