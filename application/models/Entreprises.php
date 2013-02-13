<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Application_Model_Entreprises{
    //variables correspondant à chacun des champs de notre table entreprises
    protected $_id;
    protected $_name;

    //le mapper va nous fournir les méthodes pour interagir avec notre table (objet de type Default_Model_EntreprisesMapper)
    protected $_mapper;
    //constructeur
    //le tableau d'options peut contenir les valeurs des champs à utiliser
    //pour l'initialisation de l'objet
    public function __construct(array $options = null){
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    //cette méthode permet d'appeler n'importe quel settor en fonction
    //des arguments
    public function __set($name, $value){
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid entreprises property');
        }
        $this->$method($value);
    }

    //cette méthode permet d'appeler n'importe quel gettor en fonction
    //du nom passé en argument
    public function __get($name){
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid entreprises property');
        }
        return $this->$method();
    }

    //permet de gérer un tableau d'options passé en argument au constructeur
    //ce tabelau d'options peut contenir la valeur des champs à utiliser
    //pour l'initialisation de l'objet
    public function setOptions(array $options){
        
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    /*gettors and settors d'accès aux variables
    ===========================================*/
    //id
    public function setId($id){        
        $this->_id = (int)$id;
        return $this;
    }

    public function getId(){
        return $this->_id;
    }
    
    //name
    public function setName($name){
        $this->_name = (string)$name;
        return $this;
    }
    
    public function getName(){
        return $this->_name;
    }
    
    
    //mapper
    public function setMapper($mapper){
        $this->_mapper = $mapper;
        return $this;
    }
    
    public function getMapper(){        
        //si la valeur $_mapper n'est pas initialisée, on l'initialise (
        if(null === $this->_mapper){
            $this->setMapper(new Application_Model_EntreprisesMapper());
        }
        return $this->_mapper;
    }

    //méthodes de classe utilisant les méthodes du mapper
    //crée ou met à jour une entrée dans la table
    public function save(){        
        $this->getMapper()->save($this);
    }

    //récupère une entrée particulière
    public function find($id){        
        $this->getMapper()->find($id, $this);
        return $this;
    }

    //récupère toutes les entrées de la table
    public function fetchAll(){
        return $this->getMapper()->fetchAll();
    }

    //permet la suppression
    public function delete($id){        
        $this->getMapper()->delete($id);
    }
}