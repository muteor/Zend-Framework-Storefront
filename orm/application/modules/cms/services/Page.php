<?php
/**
 * Cms_Service_Page
 *
 * @category   Cms
 * @package    Cms_Service
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Cms_Service_Page
{
    protected $_pageModel;

    public function __construct()
    {
        $this->_pageModel = new Cms_Model_Page();
    }

    public function getPageById($id)
    {
        return $this->_pageModel->getPageById($id);
    }
}
