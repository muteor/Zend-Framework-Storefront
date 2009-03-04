<?php
/**
 * SF_Model_Interface
 * 
 * All models use this interface
 * 
 * @category   Storefront
 * @package    SF_Model
 * @copyright  Copyright (c) 2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
interface SF_Model_Interface
{

    public function __construct($options = null);

    public function setOptions(array $options);

    public function setConfig(Zend_Config $config);

    public function init();
}