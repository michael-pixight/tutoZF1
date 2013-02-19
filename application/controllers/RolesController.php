<?php
class RolesController extends Zend_Controller_Action{
    //action par défaut
    public function indexAction(){
        //création d'un d'une instance Application_Model_Roles
        $roles = new Application_Model_Roles();        
        
        //$this->view permet d'accéder à la vue qui sera utilisée par l'action
        //on initialise la valeur rolesArray de la vue
        //(cf. application/views/scripts/$roles/index.phtml)
        //la valeur correspond à un tableau d'objets de type Application_Model_Roles récupérés par la méthode fetchAll()
        $this->view->rolesArray = $roles->fetchAll();
    }

    public function createAction(){
        //création du fomulaire
        $form = new Application_Form_roles();
        //indique l'action qui va traiter le formulaire
        $form->setAction($this->view->url(array('controller' => 'roles', 'action' => 'create'), 'default', true));
        $form->submit->setLabel('Create');

        //assigne le formulaire à la vue
        $this->view->form = $form;
        
        $rolesDb = new Application_Model_Roles();
        $name = $rolesDb->name;
        $this->view->rolesDb = $rolesDb;
        
        //si la page est POSTée = formulaire envoyé
        if($this->_request->isPost()){
            //récupération des données envoyées par le formulaire
            $data = $this->_request->getPost();
            //vérifie que les données répondent aux conditions des validateurs
            if($form->isValid($data)){
                //création et initialisation d'un objet Application_Model_Roles
                //qui sera enregistré dans la base de données
                
                //Pour le mot de passe on securise pas MD5 + concatenation d'un nombre au hazard               
                
                $roles = new Application_Form_Roles();                
                $roles->setName($form->getValue('name'));
                               
                $roles->save();

                //redirection
                //$this->_helper->redirector('index');
            }
            else{
                //si erreur rencontrée, le formulaire est rempli avec les données
                //envoyées précédemment
                $form->populate($data);
            }
        }
    }

    public function editAction(){
        //création du fomulaire
        $form = new Application_Form_Roles();
        //indique l'action qui va traiter le formulaire
        $form->setAction($this->view->url(array('controller' => 'roles', 'action' => 'edit'), 'default', true));
        $form->submit->setLabel('Update');

        //assigne le formulaire à la vue
        $this->view->form = $form;

        //si la page est POSTée = formulaire envoyé
        if($this->getRequest()->isPost()){
            //récupération des données envoyées par le formulaire
            $data = $this->getRequest()->getPost();

            //vérifie que les données répondent aux conditions des validateurs
            if($form->isValid($data)){
                
                //création et initialisation d'un objet Application_Model_Roles
                //qui sera enregistré dans la base de données
                $roles = new Application_Model_Roles();
                $roles->setId((int)$form->getValue('id'));
                $roles->setName($form->getValue('name'));                
                
                $roles->save();
                
                //redirection
                //$this->_helper->redirector('index');
            }
            else{
                //si erreur rencontrée, le formulaire est rempli avec les données
                //envoyées précédemment
                $form->populate($data);
            }
        }
        else{
            //récupération de l'id passé en paramètre
            $id = $this->_getParam('id', 0);
            
            if($id > -1){
                //récupération de l'entrée
                $roles = new Application_Model_Roles();
                $roles = $roles->find($id);

                //assignation des valeurs de l'entrée dans un tableau
                //tableau utilisé pour la méthode populate() qui va remplir le champs du formulaire
                //avec les valeurs du tableau
                $data[] = array();
                $data['id'] = $roles->getId();
                $data['name'] = $roles->getName();
               
                $form->populate($data);
            }
        }
    }

    public function deleteAction(){
        //récupére les paramètres de la requête
        $params = $this->getRequest()->getParams();

        //vérifie que le paramètre id existe
        if(isset($params['id'])){
            $id = $params['id'];

            //création du modèle pour la suppression
            $roles = new Application_Model_Roles();
            //appel de la fcontion de suppression avec en argument,
            //la clause where qui sera appliquée
            $result = $roles->delete("id=$id");

            //redirection
            $this->_helper->redirector('index');
        }
        else{
            $this->view->form = 'Impossible delete: id missing !';
        }
    }
}