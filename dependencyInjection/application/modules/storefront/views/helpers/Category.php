<?php
/**
 * Storefront_View_Helper_UserInfo
 *
 * Access authentication data saved in the session
 *
 * @category   Storefront
 * @package    Storefront_View_Helper
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
require_once 'Zend/View/Interface.php';

/**
 * AuthInfo helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Storefront_View_Helper_Category extends SF_View_Helper_Abstract
{
    public function  __construct()
    {
        $this->_catalogModel = $this->getContainer()->getComponent('Storefront_Model_Catalog');
    }

    public function Category()
    {
        return $this->_catalogModel->getCached()->getCategoriesByParentId(0);
    }
}
