<?php
require_once 'modules/storefront/models/resources/Product/Interface.php';
require_once 'modules/storefront/models/resources/Product/Item/Interface.php';

class Storefront_Resource_Product extends PHPUnit_Framework_TestCase implements Storefront_Resource_Product_Interface
{
    protected $_rowset = null;
    protected $_mockRow = null;
    
    public function __construct()
    {       
        $data = array();
        for($i=0; $i<10; $i++) {
            $mock = $this->getMock('Storefront_Resource_Product_Item_Interface');
            
            // mock the item calls
            $mock->expects($this->any())
                 ->method('getImages')
                 ->will($this->returnValue(array(true)));
            
            $mock->productId = $i;
            $mock->categoryId = $i;
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
    
    public function getProductById($id)
    {
        $this->assertNotEquals(0, $id, 'Assertion failed in ' . __CLASS__);
        
        foreach ($this->_rowset as $product) {
            if($product->productId == $id) {
                return $product;
            }
        }
        
        return false;
    }
    
    public function getProductByIdent($ident)
    {
        foreach ($this->_rowset as $product) {
            if($product->ident == $ident) {
                return $product;
            }
        }
        return false;
    }
    
    public function getProductsByCategory($categoryId, $limit=null, $order=null, $deep=true)
    {
        $data = array();
        foreach ($this->_rowset as $product) {
            if((int) $product->categoryId == (int) $categoryId) {
                $data[] = $product;
            }
        }
        return $data;
    }
    
    public function saveProduct($info)
    {
        return true;
    }
}