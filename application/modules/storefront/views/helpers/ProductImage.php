<?php
namespace Storefront\View\Helper;

use Zend\View\Helper,
    Storefront\Model;

/**
 * Storefront_View_Helper_ProductImage
 * 
 * Helper for displaying product images
 * 
 * @category   Storefront
 * @package    Storefront_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class ProductImage extends Helper\HtmlElement
{
    /**
     * @var Storefront_Resource_ProductImage_Item
     */
    protected $_image;
    
    /**
     * @var array|false
     */
    protected $_attribs;

    public function direct(Storefront\Model\Resource\Productimage $image = null, $attribs = false)
    {
        return $this->ProductImage($image, $attribs);
    }

    /**
     * productImage
     *
     * @param Storefront_Resource_ProductImage_Item $image
     * @param array $attribs Attributes for the img tag
     * @return Zend_View_Helper_ProductImage
     */
    public function productImage(Storefront\Model\Resource\Productimage $image = null, $attribs = false)
    {
        $this->_image = $image;
        $this->_attribs = $attribs;
        return $this;
    }
    
    /**
     * Get the thumbnail image
     *
     * @return string The img tag
     */
    public function thumbnail()
    {
        if (null !== $this->_image) {
            return $this->_createImgTag($this->_image->thumbnail);
        }
    }
    
    /**
     * Get the full image
     *
     * @return string The img tag
     */
    public function full()
    {
        if (null !== $this->_image) {
            return $this->_createImgTag($this->_image->full);
        }
    }
    
    /**
     * Create the img tag
     *
     * @param string $file The filename to link to
     * @return string The img tag
     */
    protected function _createImgTag($file)
    {
        if ($this->_attribs) {
            $attribs = $this->_htmlAttribs($this->_attribs);
        } else {
            $attribs = '';
        }
        
        $tag = 'img src="' . $this->view->baseUrl('images/product/' . $file) . '" ';
        return '<' . $tag . $attribs . $this->getClosingBracket() . self::EOL;
    }
}
