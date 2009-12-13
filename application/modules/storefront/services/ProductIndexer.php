<?php
/**
 * Storefront_Service_ProductIndexer
 *
 * Provides access to the product index
 *
 * @category   Storefront
 * @package    Storefront_Service
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Service_ProductIndexer
{
    /**
     * @var Zend_Search_Lucene_Interface
     */
    protected $_engine;

    /**
     * @var string
     */
    protected $_indexDirectory;

    /**
     * Set the engine to use for indexing
     * 
     * @param Zend_Search_Lucene_Interface $engine
     */
    public function setIndexingEngine(Zend_Search_Lucene_Interface $engine)
    {
        $this->_engine = $engine;
    }

    /**
     * Get the indexing engine, defaults to a Zend_Search_Lucene instance
     * 
     * @return Zend_Search_Lucene_Interface
     */
    public function getIndexingEngine()
    {
        if (null === $this->_engine) {
            $directory = $this->getIndexDirectory();
            if (null === $directory) {
                throw new Storefront_Service_Indexer_Exception('Index directory must be set');
            }
            if(($files = @scandir($directory)) && count($files) <= 3) {
                $this->_engine = new Zend_Search_Lucene_Proxy(new Zend_Search_Lucene($directory, true));
            } else {
                $this->_engine = new Zend_Search_Lucene_Proxy(new Zend_Search_Lucene($directory, false));
            }
        }
        return $this->_engine;
    }

    /**
     * Index a product, this method automatically checks if the document
     * already exists and updates it if found.
     *
     * @param Storefront_Model_Document_Product $document 
     */
    public function indexProduct(Storefront_Model_Document_Product $document)
    {
        $this->deleteProduct($document);
        $this->getIndexingEngine()->addDocument($document);
    }

    /**
     * Delete a product
     * 
     * @param Storefront_Model_Document_Product|int $documentOrId 
     */
    public function deleteProduct($documentOrId)
    {
        $id = $documentOrId;

        if ($documentOrId instanceof Storefront_Model_Document_Product) {
            $id = $documentOrId->getFieldUtf8Value('productId');
        }

        $docs = $this->getIndexingEngine()->find('productId:' .$id);
        foreach ($docs as $doc) {
            $this->getIndexingEngine()->delete($doc->id);
        }
    }

    /**
     * Optimize the index
     */
    public function doMaintenance()
    {
        $this->getIndexingEngine()->optimize();
    }

    /**
     * Re-index all the products
     * 
     * @param Storefront_Model_Catalog $catalogModel 
     */
    public function reIndexAllProducts(Storefront_Model_Catalog $catalogModel)
    {
        $products = $catalogModel->getAllProducts();

        if (null !== $products) {
            foreach ($products as $product) {
                $categories = $catalogModel->getCategoryChildrenIds($product->categoryId);
                foreach ($categories as $key => $catId) {
                    if (null !== $cat = $catalogModel->getCategoryById($catId)) {
                        $categories[$key] = $cat->name;
                    }
                }
                $categories[] = $catalogModel->getCategoryById($product->categoryId)->name;
                $document = new Storefront_Model_Document_Product($product, join(',', $categories));
                $this->indexProduct($document);
            }
            $this->getIndexingEngine()->commit();
            $this->doMaintenance();
        }
    }

    /**
     * @return string
     */
    public function getIndexDirectory()
    {
        return $this->_indexDirectory;
    }

    /**
     * @param string $indexDirectory
     */
    public function setIndexDirectory($indexDirectory)
    {
        $this->_indexDirectory = $indexDirectory;
    }

    /**
     * Proxy all other calls back to the indexing engine
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function  __call($name,  $arguments)
    {
        return call_user_func_array(array($this->getIndexingEngine(), $name), $arguments);
    }
}