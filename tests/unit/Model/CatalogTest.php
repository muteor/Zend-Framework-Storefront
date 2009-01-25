<?php
/**
 * TestHelper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

/**
 * Catalog Model
 */
require_once 'modules/storefront/models/Catalog.php';

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
        /**
         * Replace resources
         */
        $product = new SF_Model_Resource_Registry_Container(
            'Storefront_Model_Catalog_Product',
            'ProductStub',
            true,
            true
        );
        $category = new SF_Model_Resource_Registry_Container(
            'Storefront_Model_Catalog_Category',
            'CategoryStub',
            true,
            true
        );
        SF_Model_Resource_Registry::set($product->identifier, $product);
        SF_Model_Resource_Registry::set($category->identifier, $category);
        
        /**
         * Configure Resource Loader/Autoloader
         */
        $rl = new SF_Controller_Helper_ResourceLoader();
        $rl->initModule('storefront', Zend_Registry::get('root') . '/application/modules/storefront' );
        
        /**
         * Setup model and path for model resources
         */
        $this->_model = $rl->getModel('Catalog');
        $this->_model->getPluginLoader()->addPrefixPath( 'Test', dirname(__FILE__) . '/TestResources' );
    }
    
    protected function tearDown()
    {
        SF_Model_Resource_Registry::clear();
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
//    
//    public function test_Catalog_Can_Get_Products_By_Category()
//    {
//        $products = $this->_model->getProductsByCategory(1);
//    }
}