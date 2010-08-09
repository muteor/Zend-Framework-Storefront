<?php
namespace Storefront\Model\Document;

use Zend\Search\Lucene,
    Storefront\Model
;

/**
 * Product document
 *
 * A product document used in the Lucene index
 *
 * @category   Storefront
 * @package    Storefront_Model_Document
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Product extends Lucene\Document
{
    public function  __construct(Model\Resource\Product\Product $item, $category)
    {
        $this->addField(Lucene\Document\Field::keyword('productId', $item->productId, 'UTF-8'));
        $this->addField(Lucene\Document\Field::text('categories', $category, 'UTF-8'));
        $this->addField(Lucene\Document\Field::text('name', $item->name, 'UTF-8'));
        $this->addField(Lucene\Document\Field::unStored('description', $item->description, 'UTF-8'));
        $this->addField(Lucene\Document\Field::text('price', $this->_formatPrice($item->getPrice()) , 'UTF-8'));
    }

    protected function _formatPrice($price)
    {
        return str_pad(str_replace('.','',$price), 10, '0', STR_PAD_LEFT);
    }
}