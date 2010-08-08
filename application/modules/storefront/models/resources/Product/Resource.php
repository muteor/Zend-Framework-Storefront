<?php
namespace Storefront\Model\Resource\Product;

use SF\Model\Db as DbInterface;
/**
 * Storefront_Resource_Product_Interface
 *
 * @category   Storefront
 * @package    Storefront_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface Resource
{
    public function getProductById($id);
    public function getProductByIdent($ident);
    public function getAllProducts();
    public function getProductsByCategory($categoryId, $paged=null, $order=null);
}