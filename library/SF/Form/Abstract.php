<?php
/**
 * SF_Form_Abstract
 * 
 * Base form class provides some helpper methods for our forms.
 * 
 * @category   Storefront
 * @package    SF_Form
 * @copyright  Copyright (c) 2005-2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
abstract class SF_Form_Abstract extends Zend_Form 
{
    /**
     * @var SF_Model_Interface
     */
    protected $_model = null;
    
    /**
     * Modified Zend_Form construct helps us to share 
     * models with our forms.
     *
     * @param array $options
     * @param SF_Model_Interface $model
     */
    public function __construct($options = null, $model = null)
    {
        $this->setModel($model);
        parent::__construct($options);
    }
    
    /**
     * Set a model that this form can use
     *
     * @param SF_Model_Interface $model
     */
    public function setModel(SF_Model_Interface $model)
    {
        $this->_model = $model;
    }
}