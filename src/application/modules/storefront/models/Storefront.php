<?php
/**
 * Storefront_Model
 * 
 * All models within the Storefront module will extend from
 * this abstract class. This is used to cleany namespace our module.
 * 
 * @category   Storefront
 * @package    Storefront_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
abstract class Storefront_Model_Storefront extends SF_Model_Abstract
{
    public function initDefaults()
    {
        $this->setResourcePath(dirname(__FILE__) . '/resources');
        $this->setResourcePrefix('Storefront_Resource');
    }
}