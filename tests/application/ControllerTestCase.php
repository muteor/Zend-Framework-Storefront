<?php
require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    public $application;

    public function setUp()
    {
        $this->application = new Zend_Application(
            'test',
            APPLICATION_PATH . '/config/store.ini'
        );

        $this->bootstrap = array($this, 'appBootstrap');
        parent::setUp();
    }

    public function tearDown()
    {
        $this->resetRequest()->resetResponse();
        $this->request->setPost(array());
        $this->request->setQuery(array());
    }

    public function appBootstrap()
    {
        $this->application->bootstrap();
    }
}