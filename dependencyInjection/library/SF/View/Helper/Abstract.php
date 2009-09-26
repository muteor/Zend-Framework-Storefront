<?php
/**
 * Abstract view helper that contains the getContainer() method to provide
 * access to the Yadif container in the View Helpers that require Model Access.
 *
 * @category   Storefront
 * @package    SF_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
abstract class SF_View_Helper_Abstract extends Zend_View_Helper_Abstract
{
    public function getContainer()
    {
        $fc = Zend_Controller_Front::getInstance();
        $c = $fc->getParam('bootstrap')->getContainer();

        return $c;
    }
}