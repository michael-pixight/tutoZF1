<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype(){
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
        $view->headMeta()->setCharset('UTF-8');
        $view->headMeta()->appendHttpEquiv('X-UA-Compatible', 'IE=edge,chrome=1');
        $view->headMeta()->setName('description', 'test');
        $view->headMeta()->setName('viewport', 'width=device-width' );
        $view->headTitle()->setSeparator(' - ');
        $view->headTitle('Tutoriel Zend Framework');
    }
    
    //Initialisation des plugins, on les attache au controller frontal
    /*protected function  _initPlugins(){
        require_once "/Appli/Controller/Plugin/Acl.php";
        
        $front = Zend_Controller_Front::getInstance();
        $AclPlugin = new Appli_Contoller_Plugin_Acl();
        $front->registerPlugin( $AclPlugin );
    }*/
}
