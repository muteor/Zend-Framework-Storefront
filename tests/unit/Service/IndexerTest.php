<?php
namespace SFTest\Service;

use Storefront\Model,
    Storefront\Service,
    Zend\Application,
    Mockery as m;

/**
 * Test resources to load
 */
require_once __DIR__ . '/../Model/TestResources/Product.php';
require_once __DIR__ . '/../Model/TestResources/Category.php';

/**
 * @group migration
 */
class IndexerServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Storefront_Service_Indexer
     */
    protected $_indexer;
    
    /**
     * @var Storefront_Model_Document_Product
     */
    protected $_document;
    
    protected function setUp()
    {
        // mock Zend_Search
        $indexEngine = m::mock('Zend\Search\Lucene\Index', array(
            'delete' => 1,
            'addDocument' => 1,
            'commit' => 1,
            'optimize' => 1
        ));

        $indexed = new \stdClass();
        $indexed->id = 1;
        $indexEngine->shouldReceive('find')->andReturn(array($indexed));

        $this->_indexer = new Service\ProductIndexer();
        $this->_indexer->setIndexingEngine($indexEngine);

        // mock product
        $item = m::mock('Storefront\\Model\\Resource\\Product\\Product');
        $item->shouldReceive('getPrice')->times(1)->andReturn('10.99');
        $item->productId = 1;
        $item->name = 'test1';
        $item->description = 'description';

        // create a document for indexing
        $this->_document = new Model\Document\Product($item, 'category1,category2');
    }

    public function test_Can_GetSet_The_Index_Directory()
    {
        $this->_indexer->setIndexDirectory('/test');
        $this->assertEquals('/test', $this->_indexer->getIndexDirectory());
    }

    public function test_Indexer_Can_Index_A_Product()
    {
        $this->_indexer->indexProduct($this->_document);
    }

    public function test_Indexer_Can_Reindex_All_Products()
    {
        $options = array(
            'resources' => array(
                'Product'  => new \SFTest\Model\Resource\ProductResource(),
                'Category' => new \SFTest\Model\Resource\CategoryResource(),
            )
        );
        $catalogModel = new Model\Catalog($options);

        $this->_indexer->reIndexAllProducts($catalogModel);
    }

    public function test_Indexer_Can_Delete_A_Product()
    {
        $this->_indexer->deleteProduct(1);
        $this->_indexer->deleteProduct($this->_document);
    }

    public function test_Indexer_Can_Commit_Changes()
    {
        $this->_indexer->commit();
    }

    public function test_Indexer_Can_Do_Maintenance()
    {
        $this->_indexer->doMaintenance();
    }
}