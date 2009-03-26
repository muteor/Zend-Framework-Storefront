<?php
/**
 * Storefront_Model_Cart
 *
 * @category   Storefront
 * @package    Storefront_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Storefront_Model_Cart extends SF_Model_Abstract implements SeekableIterator, Countable, ArrayAccess
{
   /**
     * The cart item objects
     *
     * @var array
     */
    protected $_items = array();

    /**
     * Adds or updates an item contained with the shopping cart
     *
     * @param Storefront_Resource_Product_Item_Interface $product
     * @param int $qty
     * @return Storefront_Resource_Cart_Item
     */
    public function addItem(Storefront_Resource_Product_Item_Interface $product, $qty)
    {
        $item = new Storefront_Resource_Cart_Item($product, $qty);   
        $this->_items[$item->productId] = $item;
        return $item;
    }

    /**
     * Remove an item for the shopping cart
     * 
     * @param int|Storefront_Resource_Product_Item_Interface $product
     */
    public function removeItem($product)
    {
        if (is_int($product)) {
            unset($this->_items[$product]);
        }

        if ($product instanceof Storefront_Resource_Product_Item_Interface) {
            unset($this->_items[$product->productId]);
        }
    }

    /**
     * Does the given offset exist?
     *
     * @param string|int $key key
     * @return boolean offset exists?
     */
    public function offsetExists($key)
    {
        return isset($this->_items[$key]);
    }

    /**
     * Returns the given offset.
     *
     * @param string|int $key key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->_items[$key];
    }

    /**
     * Sets the value for the given offset.
     *
     * @param string|int $key
     * @param mixed $value
     */
    public function offsetSet($key, $value)
    {
        return $this->_items[$key] = $value;
    }

    /**
     * Unset the given element.
     *
     * @param string|int $key
     */
    public function offsetUnset($key)
    {
        unset($this->_items[$key]);
    }

    /**
     * Returns the current row.
     *
     * @return array|boolean current row 
     */
    public function current()
    {
        return current($this->_items);
    }

    /**
     * Returns the current key.
     *
     * @return array|boolean current key
     */
    public function key()
    {;
        return key($this->_items);
    }

    /**
     * Moves the internal pointer to the next item and
     * returns the new current item or false.
     *
     * @return array|boolean next item
     */
    public function next()
    {
        return next($this->_items);
    }

    /**
     * Reset to the first item and return.
     *
     * @return array|boolean first item or false
     */
    public function rewind()
    {
        return reset($this->_items);
    }

    /**
     * Is the pointer set to a valid item?
     *
     * @return boolean valid item?
     */
    public function valid()
    {
        return current($this->_items) !== false;
    }

    /**
     * Seek to the given index.
     *
     * @param int $index seek index
     */
    public function seek($index)
    {
        $this->rewind();
        $position = 0;

        while ($position < $index && $this->valid()) {
            $this->next();
            $position++;
        }

        if (!$this->valid()) {
            throw new SF_Model_Exception('Invalid seek position');
        }
    }

    /**
     * Count the cart items
     *
     * @return int row count
     */
    public function count()
    {
        return count($this->_items);
    }
}