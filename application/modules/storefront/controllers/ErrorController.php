<?php
namespace Storefront;

use Zend\Controller;

class ErrorController extends Controller\Action
{
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        switch (get_class($errors->exception)) {
            case 'Zend\\Controller\\Dispatcher\\Exception':
                // send 404
                $this->getResponse()
                     ->setRawHeader('HTTP/1.1 404 Not Found');
                $this->view->message = '404 page not found.';
                break;
            case 'SF\\Exception\\PageNotFound':
                // send 404
                $this->getResponse()
                     ->setRawHeader('HTTP/1.1 404 Not Found');
                $this->view->message = $errors->exception->getMessage();
                break;
            case 'SF\\Acl\\AccessDenied':
                $this->_helper->layout->setLayout('main');
                $this->view->message = $errors->exception->getMessage();
                break;
            default:
                // application error
                $this->view->message = $errors->exception->getMessage();
                break;
        }
    }
}