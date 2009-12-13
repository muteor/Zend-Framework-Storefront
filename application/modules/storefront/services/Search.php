<?php
/**
 * Storefront_Service_Search
 *
 * Provides search on the index
 *
 * @category   Storefront
 * @package    Storefront_Service
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Service_Search
{
    protected $_index;

    public function __construct($index)
    {
        $this->_index = $index;
    }

    public function query(SF_Search_Searcher_Interface $searcher)
    {
        $hits  = $this->_index->find($searcher->parse());
        return $hits;
    }
}