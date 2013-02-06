<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype(){
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');
        $view->headTitle()->setSeparator(' - ');
        $view->headTitle('Tutoriel Zend Framework');
        $view->headMeta()->setCharset('UTF-8');
    }
    
    //Initialisation des plugins, on les attache au controller frontal
    /*protected function  _initPlugins(){
        require_once "/Appli/Controller/Plugin/Acl.php";
        
        $front = Zend_Controller_Front::getInstance();
        $AclPlugin = new Appli_Contoller_Plugin_Acl();
        $front->registerPlugin( $AclPlugin );
    }*/
}
