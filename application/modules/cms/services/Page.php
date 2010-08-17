<?php
namespace Cms\Service;

use Cms\Model;

/**
 * Cms_Service_Page
 *
 * @category   Cms
 * @package    Cms_Service
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Page
{
    protected $_pageModel;

    public function __construct()
    {
        $this->_pageModel = new Model\Page();
    }

    public function getPageById($id)
    {
        return $this->_pageModel->getPageById($id);
    }
}
