<?php
/**
 * Storefront_Service_ProductSearcher
 *
 * Creates valid queries for the products index
 *
 * @category   Storefront
 * @package    Storefront_Service
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Service_ProductSearcher implements SF_Search_Searcher_Interface
{
    /**
     * @var array
     */
    protected $_data;
    
    /**
     * Constructor
     * 
     * @param array $data 
     */
    public function __construct(array $data)
    {
        $this->_data = $data;
    }

    public function parse()
    {
        return Zend_Search_Lucene_Search_QueryParser::parse($this->_data['query']);
    }
}