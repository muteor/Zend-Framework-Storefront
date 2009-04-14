<?php
/**
 * Zend_View_Helper_BaseUrl
 * 
 * Taken from proposal wiki
 * http://framework.zend.com/wiki/display/ZFPROP/Zend_View_Helper_BaseUrl
 * 
 * @author     Geoffrey Tran, Robin Skoglund
 * @category   Storefront
 * @package    Storefront_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Zend_View_Helper_BaseUrl extends Zend_View_Helper_Abstract 
{ 
    public function baseUrl($file = null) 
    { 
        // Get baseUrl 
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl(); 
 
        // Remove trailing slashes 
        $file = ($file !== null) ? ltrim($file, '/\\') : null; 
 
        // Build return 
        $return = rtrim($baseUrl, '/\\') . (($file !== null) ? ('/' . $file) : ''); 
 
        return $return; 
    } 
} 