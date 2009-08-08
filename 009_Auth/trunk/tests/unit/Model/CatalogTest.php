<?php
/**
 * TestHelper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

/**
 * Test case for Storefront_Catalog
 * 
 * This test simply tests the interface between the model
 * and the resources that it uses. This ensures that our 
 * tests will run quickly and do not need to access things
 * like the database. Databases etc will be tested during the
 * integration tests.
 */
class CatalogTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SF_Model_Interface
     */
    protected $_model;
    
    protected function setUp()
    {
        _SF_Autloader_SetUp();

        // configure the resource loader atuo load models
        $loader = new Zend_Loader_Autoloader_Resource(array(
            'basePath' => APPLICATION_PATH . '/modules/storefront',
            'namespace' => 'Storefront'
            )
        );
        $loader->addResourceType('Model', 'models', 'Model');

        // configure another loader so we can replace Model Resources
        $loader = new Zend_Loader_Autoloader_Resource(array(
            'basePath' => dirname(__FILE__),
            'namespace' => 'Storefront'
            )
        );
        $loader->addResourceType('modelResource', 'TestResources', 'Resource');
        
        $this->_model = new Storefront_Model_Catalog();
    }
    
    protected function tearDown()
    {
        _SF_Autloader_TearDown();
    }
    
    public function test_Catalog_Get_Product_By_Id_Returns_Product_Item()
    {
        $p = $this->_model->getProductById(2);
        
        $this->assertEquals(2, $p->productId);
        $this->assertEquals('Product-2', $p->ident);

        $this->assertType('Storefront_Resource_Product_Item_Interface', $p);
    }
    
    public function test_Catalog_Get_Product_By_Ident_Returns_Product_Item()
    {
        $p = $this->_model->getProductByIdent('Product-3');
        
        $this->assertEquals(3, $p->productId);
        $this->assertEquals('Product-3', $p->ident);

        $this->assertType('Storefront_Resource_Product_Item_Interface', $p);
    }

    public function test_Catalog_Product_Item_Can_Get_Images()
    {
        $p = $this->_model->getProductById(1);
        $i = $p->getImages();
        
        $this->assertType('array', $i);
    }
    
    public function test_Catalog_Can_Get_Categories_By_parentId()
    {
        $cats= $this->_model->getCategoriesByParentId(0);
        
        $this->assertEquals(6, count($cats));
    }
    
    public function test_Catalog_Can_Get_Category_By_Ident()
    {
        $category = $this->_model->getCategoryByIdent('Category-5');
        
        $this->assertType('Storefront_Resource_Category_Item_Interface', $category);
        $this->assertEquals(5, $category->categoryId);
    }
    
    public function test_Catalog_Can_Get_Category_Parent()
    {
        $category = $this->_model->getCategoryByIdent('Category-8');
        $parent   = $category->getParentCategory();

        $this->assertType('Storefront_Resource_Category_Item_Interface', $parent);
        $this->assertEquals(7, $parent->categoryId);
    }

    public function test_Catalog_Can_Get_Products_By_CategoryId()
    {
        $products = $this->_model->getProductsByCategory(1, false, null, true);
        $this->assertEquals(1, $products[0]->categoryId);
    }

    public function test_Catalog_Can_Get_Products_By_CategoryIdent()
    {
        $products = $this->_model->getProductsByCategory('Category-1', false, null, true);
        $this->assertEquals(1, $products[0]->categoryId);
    }

    public function test_Catalog_Can_Get_Category_Children_Ids()
    {
        $ids =$this->_model->getCategoryChildrenIds(6, true);
        $this->assertEquals(3, count($ids));
    }

    public function test_Catalog_Can_Get_Category_Parents()
    {
        $category = $this->_model->getCategoryByIdent('Category-8');
        $cats =$this->_model->getParentCategories($category);
        $this->assertEquals(4, count($cats));

        $category = $this->_model->getCategoryByIdent('Category-1');
        $cats =$this->_model->getParentCategories($category);
        $this->assertEquals(1, count($cats));
    }
}