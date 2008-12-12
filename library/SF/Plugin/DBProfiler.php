<?php
/**
 * SF_Plugin_DBProfiler
 * 
 * This plugin adds the query profiler to the view
 *
 * @category   Storefront
 * @package    SF_Plugin
 * @copyright  Copyright (c) 2005-2008 Keith Pope (http://www.thepopeisdead.com)
 * @license    http://www.thepopeisdead.com/license.txt     New BSD License
 */
class SF_Plugin_DBProfiler extends Zend_Controller_Plugin_Abstract 
{
    protected $_view;
    
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $this->_view = $viewRenderer->view;
            
        $db = Zend_Registry::get('db');
        $profiler = $db->getProfiler();
        
        $totalTime    = $profiler->getTotalElapsedSecs();
        $queryCount   = $profiler->getTotalNumQueries();
        $longestTime  = 0;
        $longestQuery = null;
        
        $qp = $profiler->getQueryProfiles();
        if (is_array($qp)) {
            foreach ($qp as $query) {
                if ($query->getElapsedSecs() > $longestTime) {
                    $longestTime  = $query->getElapsedSecs();
                    $longestQuery = $query->getQuery();
                }
            }
        }
        
        $this->_view->dbProfiler       = true;
        $this->_view->dbp_totalTime    = $totalTime;
        $this->_view->dbp_queryCount   = $queryCount;
        $this->_view->dbp_longestTime  = $longestTime;
        $this->_view->dbp_longestQuery = $longestQuery;
        $this->_view->qp = $qp;
    }
}