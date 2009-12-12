<?php
/**
 *  Storefront_Model_Document_Product
 *
 * A product document used in the Lucene index
 *
 * @category   Storefront
 * @package    Storefront_Model_Document
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Model_Document_Product extends Zend_Search_Lucene_Document
{
    public function  __construct(Storefront_Resource_Product_Item_Interface $item, $category)
    {
        $this->addField(Zend_Search_Lucene_Field::keyword('productId', $item->productId, 'UTF-8'));
        $this->addField(Zend_Search_Lucene_Field::text('categories', $category, 'UTF-8'));
        $this->addField(Zend_Search_Lucene_Field::text('name', $item->name, 'UTF-8'));
        $this->addField(Zend_Search_Lucene_Field::unStored('description', $item->description, 'UTF-8'));
        $this->addField(Zend_Search_Lucene_Field::text('price', $this->_formatPrice($item->getPrice()) , 'UTF-8'));
    }

    protected function _formatPrice($price)
    {
        return str_pad(str_replace('.','',$price), 10, '0', STR_PAD_LEFT);
    }
}