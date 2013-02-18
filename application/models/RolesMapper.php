<?php
//"Default" est le namespace défini dans le bootstrap
class Application_Model_RolesMapper{
    //$_dbTable va faire référence à un objet Zend_Db_Table_Abstract
    //dans notre cas la classe Default_Model_DbTable_Entreprises
    //du fichier application/models/DbTable/Entreprises.php
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
 
    //guetter
    public function getDbTable(){
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Entreprises');
        }
        return $this->_dbTable;
    }
 
        //sauve une nouvelle entrée dans la table
    public function save(Application_Model_Roles $roles){
        //récupération dans un tableau des données de l'objet $entreprises
        //les noms des clés du tableau correspondent aux noms des champs de la table
        $data = array(
                'name' => $roles->getName(),
                
                
        );

        //on vérifie si un l'objet $entreprises contient un id
        //si ce n'est pas le cas, il s'agit d'un nouvel enregistrement
        //sinon, c'est une mise à jour d'une entrée à effectuer
        if(null === ($id = $roles->getId()))
        {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        }
        else
        {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }
 
        //récupére une entrée dans la table
    public function find($id, Application_Model_Roles $roles){
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }

        //initialisation de la variable $row avec l'entrée récupérée
        $row = $result->current();

        //setting des valeurs dans notre objet $entreprises passé en argument
        $roles->setId($row->id);
        $roles->setName($row->name);        
    }
 
        //récupére toutes les entrées de la table
    public function fetchAll(){
        //récupération dans la variable $resultSet de toutes les entrées de notre table
        $resultSet = $this->getDbTable()->fetchAll();

        //chaque entrée est représentée par un objet Default_Model_Entreprises
        //qui est ajouté dans un tableau
        $entries = array();
        foreach($resultSet as $row)
        {
            $entry = new Application_Model_Roles();
            $entry->setId($row->id);            
            $entry->setName($row->name);            
            
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
}