<?php
/**
 * Cms_Bootstrap
 *
 * @category   Cms
 * @package    Cms_Bootstrap
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Cms_Bootstrap extends Zend_Application_Module_Bootstrap
{
    public function _initModuleResourceAutoloader()
    {
        $this->getResourceLoader()->addResourceTypes(array(
            'modelResource' => array(
              'path'      => 'models/resources',
              'namespace' => 'Resource',
            )
        ));
    }
}