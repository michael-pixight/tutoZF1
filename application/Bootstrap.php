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
    
    protected function _initAutoloadRessource(){
        //configuration de l'Autoload
        $ressourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'namespace' => 'Application',
            'basePath'  => dirname(__FILE__),
        ));
        
        //echo '<pre>', print_r($ressourceLoader, 1), '</pre>';
        //permet d'indiquer les répertoires dans lesquels se trouveront nos classes:
        //notamment, l'ACL et le pugin
        $ressourceLoader->addResourceType('acl', 'acls/', 'Acl')                       
                        ->addResourceType('plugin', 'plugins/', 'Controller_Plugin');
        
        //Zend_Debug::dump($ressourceLoader);
        //echo '<pre>', print_r($ressourceLoader, 1), '</pre>';
 
        return $ressourceLoader;
    }
    
    protected function _initAcl(){
        //je setDefaultAdapter a l'initialisation de mon Acl pour acceder à la base et recupere les roles, users...
        //Ca revient a Zend_Db_Table::setDefaultAdapter($db); sans le definire xplicitement
        //http://stackoverflow.com/questions/7029307/no-default-adapters-in-zend-unless-i-add-them-explicitly-is-this-a-feature-or-a
        //http://www.z-f.fr/forum/viewtopic.php?id=1186
        $this->bootstrap('db');
        //Création d'une instance de notre ACL        
        $acl = new Application_Acl_MyAcl();

        //enregistrement du plugin de manière à ce qu'il soit exécuté
        Zend_Controller_Front::getInstance()->registerPlugin(new Application_Controller_Plugin_Acl() );
        
        //permet de définir l'acl par défaut à l'aide de vue, de cette manière
        //l'ACL est accessible dans les vues
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
        
        //vérifie si une identité existe et applique le rôle
        $auth = Zend_Auth::getInstance();
        $role = (!$auth->hasIdentity()) ? 'guest' : $auth->getIdentity()->role;
        //echo '<pre>', print_r($auth, 1), '</pre>';
        //echo '<pre>', print_r($role, 1), '</pre>';
    }
 
}
