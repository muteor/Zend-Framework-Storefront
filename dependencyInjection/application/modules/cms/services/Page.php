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
    protected $_model;

    public function getPageById($id)
    {
        return $this->getModel()->getPageById($id);
    }

    public function setModel(SF_Model_Interface $model)
    {
        $this->_model = $model;
    }

    public function getModel()
    {
        return $this->_model;
    }
}
