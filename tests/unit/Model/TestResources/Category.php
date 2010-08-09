<?php
namespace SFTest\Model\Resource;

use Storefront\Model\Resource\Category,
    Mockery as m;

class CategoryResource implements Category\Resource
{
    protected $_products = null;
    protected $_categories = null;
    
    function __construct ()
    {
        // create some test products
        $data = array();
        for($i=0; $i<10; $i++) {
            $mock = m::mock('Storefront\\Model\\Resource\\Product\\Product');
            $mock->shouldReceive('getImages')->andReturn(array(true));
            
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
            $mock = m::mock('Storefront\\Model\\Resource\\Category\\Category');
            
            $mock->categoryId = $i;
            $mock->name = 'Category ' . $i;
            $mock->parentId = $i > 5 ? ($i-1) : 0; 
            $mock->ident = 'Category-' . $i;

            if($i > 0) {
                $mock->shouldReceive('getParentCategory')
                      ->andReturn($data[$i-1]);
            }
            $data[] = $mock;
        }
        
        $this->_categories = $data;
    }
    
    public function getCategoriesByParentId($parentId)
    {        
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
        foreach ($this->_categories as $cat) {
            if ($ident === $cat->ident) {
               return $cat;
            }
        }
        return null;
    }
}
