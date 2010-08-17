<?php
namespace Storefront\View\Helper;

use Zend\View\Helper,
    Storefront\Model;
/**
 * Storefront_View_Helper_SearchResult
 *
 * Display the search result
 *
 * @category   Storefront
 * @package    Storefront_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SearchResult extends Helper\AbstractHelper
{
    public function direct($hit)
    {
        return $this->SearchResult($hit);
    }

    public function searchResult($hit)
    {
        $catalog = new Model\Catalog();
        $product = $catalog->getProductById($hit->productId);

        if (null !== $product) {
            $category = $catalog->getCategoryById($product->categoryId);
            $this->view->product = $product;
            $this->view->category = $category;
            return $this->view->render('index/_result.phtml');
        }
    }
}