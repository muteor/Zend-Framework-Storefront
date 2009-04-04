<?php
class Storefront_ErrorController extends Zend_Controller_Action 
{
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // send 404
                $this->getResponse()
                     ->setRawHeader('HTTP/1.1 404 Not Found');
                $this->view->message = '404 page not found.';
                break;
            case 'SF_Exception_404':
                // send 404
                $this->getResponse()
                     ->setRawHeader('HTTP/1.1 404 Not Found');
                $this->view->message = $errors->exception->getMessage();
                break;
            default:
                // application error
                $this->view->message = $errors->exception->getMessage();
                break;
        }
    }
}