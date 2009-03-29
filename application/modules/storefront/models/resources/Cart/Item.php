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
class Storefront_Resource_Cart_Item implements Storefront_Resource_Cart_Item_Interface
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
        $this->price                 = (float) $product->getPrice(false,false);
        $this->taxable              = $product->taxable;
        $this->discountPercent  = (int) $product->discountPercent;
        $this->qty                    = (int) $qty;
    }

    public function getLineCost()
    {
        $price = $this->price;

        if (0 !== $this->discountPercent) {
            $discounted = ($price*$this->discountPercent)/100;
            $price = round($price - $discounted, 2);
        }

        if ('Yes' === $this->taxable) {
            $taxService = new Storefront_Service_Taxation();
            $price = $taxService->addTax($price);
        }
       
        return $price * $this->qty;
    }
}