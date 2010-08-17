<?php
namespace Storefront\Model;

use SF\Model;

/**
 * Storefront_Model_Shipping
 *
 * @category   Storefront
 * @package    Storefront_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Shipping extends Model\AbstractModel
{
    /**
     * @var array
     */
    protected $_shippingData = array(
        'Standard' => 1.99,
        'Special'  => 5.99,
    );

    /**
     * Get the shipping options
     * 
     * @return array
     */
    public function getShippingOptions()
    {
        return $this->_shippingData;
    }
}