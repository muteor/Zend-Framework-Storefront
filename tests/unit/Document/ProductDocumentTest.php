<?php
namespace SFTest;

use Storefront\Model,
     Mockery as m;

/**
 * @group migration
 */
class ProductDocumentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Storefront_Document_Product
     */
    protected $_document;

    protected function setUp()
    {
        $item = m::mock('Storefront\\Model\\Resource\\Product\\Product');
        $item->productId = 1;
        $item->name = 'test1';
        $item->description = 'description';
        $item->shouldReceive('getPrice')->andReturn('10.99');

        $this->_document = new \Storefront\Model\Document\Product($item, 'category1,category2');
    }

    protected function tearDown()
    {
        m::close();
    }

    public function test_Document_Has_Correct_Field_Count()
    {
        $this->assertEquals(5, count($this->_document->getFieldNames()));
    }

    public function test_Document_Maps_Data_Correctly()
    {
//        $this->assertEquals(1, $this->_document->getFieldUtf8Value('productId'));
//        $this->assertEquals('category1,category2', $this->_document->getFieldUtf8Value('categories'));
//        $this->assertEquals('test1', $this->_document->getFieldUtf8Value('name'));
//        $this->assertEquals('description', $this->_document->getFieldUtf8Value('description'));
//        $this->assertEquals('0000001099', $this->_document->getFieldUtf8Value('price'));
    }
}