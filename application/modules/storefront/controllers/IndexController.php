<?php
class Storefront_IndexController extends Zend_Controller_Action
{
    protected $_forms = array();
    protected $_catalogModel;

    public function init()
    {
        $this->_catalogModel = new Storefront_Model_Catalog();
    }

    public function indexAction()
    {
        if ($service = $this->_helper->service('page', 'cms')) {
            $this->view->page = $service->getPageById(1);
        }
    }

    public function searchAction()
    {
        $request = $this->getRequest();

        if (!$request->isGet()) {
            return $this->_helper->redirector('index');
        }

        if (!$this->_getSearchForm()->isValid($request->getQuery())) {
            return $this->_helper->redirector('index');
        }

        $searchService = new Storefront_Service_Search(
            $this->_catalogModel->getIndexer()
        );

        $searcher = new Storefront_Service_ProductSearcher(
            $this->_getSearchForm()->getValues()
        );

        $this->view->results = $searchService->query($searcher);
    }

    protected function _getSearchForm()
    {
        $urlHelper = $this->_helper->getHelper('url');

        $this->_forms['search'] = $this->_catalogModel->getForm('searchBase');
        $this->_forms['search']->setAction($urlHelper->url(array(
            'controller' => 'index' ,
            'action' => 'search'
            ),
            'default'
        ));
        $this->_forms['search']->setMethod('get');

        return $this->_forms['search'];
    }
}