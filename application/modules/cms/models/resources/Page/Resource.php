<?php
namespace Cms\Model\Resource\Page;

/**
 * Cms_Resource_Page_Interface
 *
 * @category   Cms
 * @package    Cms_Model_Resource
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface Resource
{
    public function getPageById($id);
}