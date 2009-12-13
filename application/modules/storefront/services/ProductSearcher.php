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
        $data = $this->_data;

        $query = Zend_Search_Lucene_Search_QueryParser::parse($data['query']);

        if ('' != $data['pricefrom'] && '' != $data['priceto']) {
            $from = new Zend_Search_Lucene_Index_Term(
                $this->_formatPrice($data['pricefrom']),
                'price'
            );
            $to = new Zend_Search_Lucene_Index_Term(
                $this->_formatPrice($data['priceto']),
                'price'
            );
            $q = new Zend_Search_Lucene_Search_Query_Range(
                 $from, $to, true // inclusive
             );
            $query = Zend_Search_Lucene_Search_QueryParser::parse($data['query'] . ' +' . $q);
        }
        
        return $query;
    }

    protected function _formatPrice($price)
    {
        $price = (int) $price;
        return str_pad(str_replace('.','',$price * 100), 10, '0', STR_PAD_LEFT);
    }
}