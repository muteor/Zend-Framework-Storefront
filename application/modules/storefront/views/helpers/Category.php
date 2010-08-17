<?php
namespace Storefront\View\Helper;

use Zend\View\Helper,
    Storefront\Model;

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

/**
 * AuthInfo helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Category extends Helper\AbstractHelper
{
    public function direct()
    {
        return $this->Category();
    }

    public function Category()
    {
        $catalogModel = new Model\Catalog();
        return $catalogModel->getCached()->getCategoriesByParentId(0);
    }
}
