<?php

class Application_Acl_MyAcl extends Zend_Acl{
    
    private $_db;
    
    
    public function __construct() {
        
        /*$params = array(
            'host'     => '127.0.0.1',
            'username' => 'root',
            'password' => 'root',
            'dbname'   => 'tutozf1',
            'profiler' => true  // active le profileur ;
                                // mettre à false pour désactiver
                                // (désactivé par défaut)
        );
        $this->_db = Zend_Db::factory('PDO_MYSQL', $params);  */
        $this->_initRessources();
        $this->_initRoles();
        $this->_initRights();
        
        //Zend_Registry permet de gerer une collection de valeurs accessible n'importe ou dans notre applicatif, on peut l'assimiler a une variable globale.
        Zend_Registry::set('MyAcl', $this);
        
    }
    
    private function _initRessources(){
        //Création des ressources
        //une ressource correspond à un élément pour lequel l'acces est controlé
        //Un ressource par controller
        $this->addResource(new Zend_Acl_Resource('index'));
		$this->addResource(new Zend_Acl_Resource('error'));
		$this->addResource(new Zend_Acl_Resource('auth'));
		$this->addResource(new Zend_Acl_Resource('users'));
        
    }
    
    protected function _initRoles(){
        
        /*$roles = $this->_db->fetchAll(
                
            $this->_db->select()
                ->from('roles')
                ->order(array('role_id DESC'))
        );*/
        
        //echo '<pre>', print_r($roles, 1), '</pre>';
        /*$this->addRole(new Zend_Acl_Role($roles[0]['name']));
 
        for ($i = 1; $i < count($roles); $i++) {
            $this->addRole(new Zend_Acl_Role($roles[$i]['name']), $roles[$i-1]['name']);
        }*/
        
        
        //Création des rôles
        //Un rôle est un objet qui demande l'acces aux ressources.
        //Nous définissons 4 rôles :
        // superadmin
        // administrator
        // user
        // guest
        
        
        $roles = new Application_Model_roles();
        $fetchRoles = $roles->fetchAll();
        
        foreach ($fetchRoles as $roles ) :
            $arrayRoles[$roles->id] = $roles->name;
            $this->addRole( new Zend_Acl_Role($roles->name) );
        endforeach;
        
        
        
        Zend_Debug::dump($this->getRoles());
        
        
        /*$guest = new Zend_Acl_Role('guest');
		$user = new Zend_Acl_Role('user');
		$admin = new Zend_Acl_Role('admin');
        $superAdmin = new Zend_Acl_Role('superAdmin');*/
        
        //$acl->addRole(new Zend_Acl_Role('marketing'), 'staff');
        //$SuperAdmin = new Zend_Acl_Role('superadmin');
        
        //Ajout des roles à l'ACL avec la methode addRole(), le premier arguement est le role à ajouter, le second indique l'heritage
        /*$this->addRole($guest);
        $this->addRole($user, 'guest');
        $this->addRole($admin, 'user');
        $this->addRole($superAdmin, 'admin');
        //$this->addRole($SuperAdmin, $administrator);*/
    }
    
    protected function _initRights(){
        //définition des règles
        //la methode allow permet d'indiquer les permission pour chaque rôle, la mmethoed deny indique le refus
        //Premier argument permet de definir le rôle pour qui la règle est écrite
        //Second argument permet d'indiquer les contrôleurs
        //Troisieme argument permet d'indiquer les actions du contrôleur. 
        $this->allow( 'guest', array('index', 'error', 'auth'), array('index', 'login', 'logout') );
        $this->allow('user', 'users', 'index');
        $this->allow('admin' );
        $this->allow('superadmin');
    }
}