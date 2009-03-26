<?php
/**
 * Storefront_Resource_Cart_Item
 *
 * Simple value object used by the Cart Model
 *
 * @category   Storefront
 * @package    Storefront_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Resource_Cart_Item
{
    public $productId;
    public $name;
    public $price;
    public $taxable;
    public $discountPercent;
    public $qty;

    public function __construct(Storefront_Resource_Product_Item_Interface$product, $qty)
    {
        $this->productId           = (int) $product->productId;
        $this->name                 = $product->name;
        $this->price                 = $product->price;
        $this->taxable              = $product->taxable;
        $this->discountPercent  = $product->discountPercent;
        $this->qty                    = (int) $qty;
    }
}