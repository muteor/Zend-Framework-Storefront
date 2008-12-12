<?php
/**
 * SF_Model_Interface
 * 
 * All models use this interface
 * 
 * @category   Storefront
 * @package    SF_Model
 * @copyright  Copyright (c) 2005-2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface SF_Model_Interface {
    public function addResource($name, $isDefault=false, $lock=false, $className=null);
    public function getResource($name=null);
}