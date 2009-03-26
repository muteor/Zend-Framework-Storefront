<?php
/**
 * TestHelper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

/**
 * Product
 */
require_once APPLICATION_PATH . '/modules/storefront/models/resources/Product/Item/Interface.php';

class CartTest extends PHPUnit_Framework_TestCase
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
        $loader->addResourceType('ModelResource', 'models/resources', 'Resource');
        $loader->addResourceType('Form', 'forms', 'Form');

        $this->_model = new Storefront_Model_Cart();
    }
    
    protected function tearDown()
    {
        _SF_Autloader_TearDown();
        $this->_model = null;
    }

    public function test_Cart_Is_Countable()
    {
        $this->assertEquals(0, count($this->_model));
    }

    public function test_Cart_Can_Add_An_Item()
    {
        $product = $this->getMock('Storefront_Resource_Product_Item_Interface');
        $product->productId = 1;
        $product->categoryId = 2;
        $product->ident = 'Product-1';
        $product->name = 'Product 1';
        $product->description = str_repeat('Product 1 is great..', 10);
        $product->shortDescription = 'Product 1 is great..';
        $product->price = '10.99';
        $product->discountPercent = 0;
        $product->taxable = 'Yes';
        $product->deliveryMethod = 'Mail';
        $product->stockStatus = 'InStock';

        $cartItem = $this->_model->addItem($product, 10);
        $cartItem = $this->_model->addItem($product, 10);
        $cartItem = $this->_model->addItem($product, 10);

        $this->assertType('Storefront_Resource_Cart_Item', $cartItem);
    }
}