<?php
namespace Cms;

use Zend\Application\Module\Bootstrap as ModuleBootstrap;

/**
 * Cms\Bootstrap
 *
 * @category   Cms
 * @package    Cms_Bootstrap
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class Bootstrap extends ModuleBootstrap
{
    public function _initModuleResourceAutoloader()
    {
        $this->getResourceLoader()->addResourceTypes(array(
            'modelResource' => array(
              'path'      => 'models/resources',
              'namespace' => 'Model\\Resource',
            )
        ));
    }
}