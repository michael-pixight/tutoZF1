<?php
//"Default" est le namespace défini dans le bootstrap
class Application_Model_UsersMapper{
    //$_dbTable va faire référence à un objet Zend_Db_Table_Abstract
    //dans notre cas la classe Default_Model_DbTable_Users
    //du fichier application/models/DbTable/Users.php
    protected $_dbTable;
 
        //setter
    public function setDbTable($dbTable){
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    //getter
    public function getDbTable(){
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Users');
        }
        return $this->_dbTable;
    }
 
    //sauve une nouvelle entrée dans la table
    public function save(Application_Model_Users $users){
        //récupération dans un tableau des données de l'objet $users
        //les noms des clés du tableau correspondent aux noms des champs de la table        
        $data = array(
                'firstname' => $users->getFirstname(),
                'lastname' => $users->getLastname(),           
                'mail' => $users->getMail(),
                'username' => $users->getUsername(),                
                'password' => $users->getPassword(),
                'salt' => $users->getSalt(),
                'role' => $users->getRole(),
                'roles_id' => $users->getRolesId(), //nom de la colonne
        );
        echo '<pre>', print_r($data['roles_id'], 1), '</pre>';
        //on vérifie si un l'objet $users contient un id
        //si ce n'est pas le cas, il s'agit d'un nouvel enregistrement
        //sinon, c'est une mise à jour d'une entrée à effectuer
        if(null === ($id = $users->getId())){
            unset($data['id']);
            $this->getDbTable()->insert($data);
        }
        else{
            $this->getDbTable()->update($data, array('user_id = ?' => $id));
        }
    }
 
        //récupére une entrée dans la table
    public function find($id, Application_Model_Users $users){
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }

        //initialisation de la variable $row avec l'entrée récupérée
        $row = $result->current();

        //setting des valeurs dans notre objet $users passé en argument
        $users->setId($row->user_id);
        $users->setFirstname($row->firstname);
        $users->setLastname($row->lastname);
        $users->setMail($row->mail);
        $users->setUsername($row->username);        
        $users->setPassword($row->password);
        $users->setSalt($row->salt);        
        $users->setRole($row->role);
        $users->setRolesId($row->roles_id);
    }
 
        //récupére toutes les entrées de la table
    public function fetchAll(){
        //récupération dans la variable $resultSet de toutes les entrées de notre table
        $resultSet = $this->getDbTable()->fetchAll();
        
        $roleMapper = new Application_Model_RolesMapper();
        
        $resultRolesSet = $roleMapper->getDbTable()->fetchAll();
        
        
        //chaque entrée est représentée par un objet Application_Model_Users
        //qui est ajouté dans un tableau
        $entries = array();
        foreach($resultSet as $row)
        {
            $entry = new Application_Model_Users();
            $entry->setId($row->user_id);            
            $entry->setFirstname($row->firstname);
            $entry->setLastname($row->lastname);
            $entry->setMail($row->mail);
            $entry->setUsername($row->username);            
            $entry->setPassword($row->password);
            $entry->setSalt($row->salt);
            $entry->setRole($row->role);
            //a chaque entrée role id d'un user correspond un name de la table role, dont l'id est egale
            
            $entry->setRolesId( $this->getRoleName($row->roles_id));
            
            $entry->setMapper($this);

            $entries[] = $entry;
        }

        return $entries;
    }    
 
    //permet de supprimer un utilisateur,
    //reçoit la condition de suppression (le plus souvent basé sur l'id)
    public function delete($id){
        $result = $this->getDbTable()->delete($id);
    }
    
    
    public function getRoleName($role){
        
        $roleMapper = new Application_Model_RolesMapper();        
        $resultRolesSet = $roleMapper->getDbTable()->fetchAll();
        foreach ( $resultRolesSet as $rowRoles){            
            if($role == $rowRoles->id){
                $rolesName = $rowRoles->name;
                return $rolesName;
            }else{
                $rolesName = 'guest';               
            }
        }
        return $rolesName;
        
    }
}