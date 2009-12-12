<?php
/**
 * TestHelper
 */
require_once dirname(__FILE__) . '/../../TestHelper.php';

require_once APPLICATION_PATH . '/modules/storefront/models/document/Product.php';
require_once APPLICATION_PATH . '/modules/storefront/models/resources/Product/Item/Interface.php';
require_once APPLICATION_PATH . '/modules/storefront/services/ProductIndexer.php';
require_once 'Zend/Application.php';
require_once dirname(__FILE__) . '/_files/ZendSearchStub.php';
require_once dirname(__FILE__) . '/_files/CatalogStub.php';

class IndexerServiceTest extends PHPUnit_Framework_TestCase
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
        // get the config
        $application = new Zend_Application(
            'development',
            APPLICATION_PATH . '/config/store.ini'
        );
        $application->bootstrap('ZendSearch');

        // mock Zend_Search
        $indexEngine = new Zend_Search_Stub();

        $this->_indexer = new Storefront_Service_ProductIndexer();
        $this->_indexer->setIndexingEngine($indexEngine);

        // mock product
        $item = $this->getMock('Storefront_Resource_Product_Item_Interface');
        $item->productId = 1;
        $item->name = 'test1';
        $item->description = 'description';
        $item->expects($this->once())->method('getPrice')->will($this->returnValue('10.99'));

        // create a document for indexing
        $this->_document = new Storefront_Model_Document_Product($item, 'category1,category2');
    }

    public function test_Indexer_Has_Correct_Config()
    {
        $analyzer = Zend_Search_Lucene_Analysis_Analyzer::getDefault();
        $this->assertType('Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive', $analyzer);
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
        $catalogModel = new Catalog_Stub();
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