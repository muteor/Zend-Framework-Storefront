<?php
/**
 * TestHelper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once APPLICATION_PATH . '/modules/storefront/models/document/Product.php';
require_once APPLICATION_PATH . '/modules/storefront/models/resources/Product/Item/Interface.php';

class ProductDocumentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Storefront_Document_Product
     */
    protected $_document;

    protected function setUp()
    {
        $item = $this->getMock('Storefront_Resource_Product_Item_Interface');
        $item->productId = 1;
        $item->name = 'test1';
        $item->description = 'description';
        $item->expects($this->once())->method('getPrice')->will($this->returnValue('10.99'));

        $this->_document = new Storefront_Model_Document_Product($item, 'category1,category2');
    }

    public function test_Document_Has_Correct_Field_Count()
    {
        $this->assertEquals(5, count($this->_document->getFieldNames()));
    }

    public function test_Document_Maps_Data_Correctly()
    {
        $this->assertEquals(1, $this->_document->getFieldUtf8Value('productId'));
        $this->assertEquals('category1,category2', $this->_document->getFieldUtf8Value('categories'));
        $this->assertEquals('test1', $this->_document->getFieldUtf8Value('name'));
        $this->assertEquals('description', $this->_document->getFieldUtf8Value('description'));
        $this->assertEquals('0000001099', $this->_document->getFieldUtf8Value('price'));
    }
}