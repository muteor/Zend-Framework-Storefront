<?php
namespace SFTest\Model\Resource;

use Storefront\Model\Resource\Product,
    Mockery as m;

class ProductResource implements Product\Resource
{
    protected $_rowset = null;
    protected $_mockRow = null;
    
    public function __construct()
    {       
        $data = array();
        for($i=0; $i<10; $i++) {
            $mock = m::mock('Storefront\\Model\\Resource\\Product\\Product');
            $mock->shouldReceive('getImages')->andReturn(array(true));
            
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

            $mock->shouldReceive('getPrice')->andReturn($mock->price);
            
            $data[] = $mock;
        }

        $this->_rowset = $data;
    }
    
    public function getProductById($id)
    {   
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

    public function getAllProducts()
    {
        return $this->_rowset;
    }
}