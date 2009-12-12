<?php
require_once APPLICATION_PATH . '/modules/storefront/models/Catalog.php';
class Resource_Stub extends PHPUnit_Framework_TestCase
{
    public function getAllProducts()
    {
        $data = array();
        for($i=0; $i<10; $i++) {
            $mock = $this->getMock('Storefront_Resource_Product_Item_Interface');

            // mock the item calls
            $mock->expects($this->any())
                 ->method('getPrice')
                 ->will($this->returnValue('10.99'));

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
        return $data;
    }

    public function getCategoriesByParentId()
    {
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

        return $data;
    }

    public function getCategoryById($id)
    {
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

        return $data[$id];
    }
}
class Catalog_Stub extends Storefront_Model_Catalog
{
    public function  __construct($options = null) {
        
    }

    public function init(){}

    public function getResource($name) {
        return new Resource_Stub();
    }
}
