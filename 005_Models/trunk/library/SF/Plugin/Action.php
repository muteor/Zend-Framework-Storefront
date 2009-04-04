<?php
/**
 * Application Action Plugin
 * 
 * This plugin uses the action stack to provide global
 * view segments.
 * 
 * @category   Storefront
 * @package    SF_Plugin
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Plugin_Action extends Zend_Controller_Plugin_Abstract 
{
    protected $_stack;

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) 
    {
        $stack = $this->getStack();
        
        // category menu
        $categoryRequest = new Zend_Controller_Request_Simple();
        $categoryRequest->setControllerName('category')
                        ->setActionName('index')
                        ->setParam('responseSegment', 'categoryMain');

        // push requests into the stack
        $stack->pushStack($categoryRequest);
    }

    public function getStack()
    {
        if (null === $this->_stack) {
            $front = Zend_Controller_Front::getInstance();
            if (!$front->hasPlugin('Zend_Controller_Plugin_ActionStack')) {
                $stack = new Zend_Controller_Plugin_ActionStack();
                $front->registerPlugin($stack);
            } else {
                $stack = $front->getPlugin('ActionStack');
            }
            $this->_stack = $stack;
        }
        return $this->_stack;
    }
}