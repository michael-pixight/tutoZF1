<?php
class EntreprisesController extends Zend_Controller_Action{
    //action par défaut
    public function indexAction(){
        //création d'un d'une instance Default_Model_Entreprises
        $entreprises = new Application_Model_Entreprises();        
        
        //$this->view permet d'accéder à la vue qui sera utilisée par l'action
        //on initialise la valeur entreprisesArray de la vue
        //(cf. application/views/scripts/$entreprises/index.phtml)
        //la valeur correspond à un tableau d'objets de type Default_Model_Entreprises récupérés par la méthode fetchAll()
        $this->view->entreprisesArray = $entreprises->fetchAll();
    }

    public function createAction(){
        //création du fomulaire
        $form = new Application_Form_Entreprises();
        //indique l'action qui va traiter le formulaire
        $form->setAction($this->view->url(array('controller' => 'entreprises', 'action' => 'create'), 'default', true));
        $form->submit->setLabel('Create');

        //assigne le formulaire à la vue
        $this->view->form = $form;
        
        $entreprisesDb = new Application_Model_Entreprises();
        $name = $entreprisesDb->name;
        $this->view->entreprisesDb = $entreprisesDb;
        
        //si la page est POSTée = formulaire envoyé
        if($this->_request->isPost()){
            //récupération des données envoyées par le formulaire
            $data = $this->_request->getPost();
            //vérifie que les données répondent aux conditions des validateurs
            if($form->isValid($data)){
                //création et initialisation d'un objet Default_Model_Entreprises
                //qui sera enregistré dans la base de données
                
                //Pour le mot de passe on securise pas MD5 + concatenation d'un nombre au hazard               
                
                $entreprises = new Application_Model_Entreprises();
                $entreprises->setFirstname($form->getValue('name'));
                               
                $entreprises->save();

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
        $form = new Application_Form_Entreprises();
        //indique l'action qui va traiter le formulaire
        $form->setAction($this->view->url(array('controller' => 'entreprises', 'action' => 'edit'), 'default', true));
        $form->submit->setLabel('Update');

        //assigne le formulaire à la vue
        $this->view->form = $form;

        //si la page est POSTée = formulaire envoyé
        if($this->getRequest()->isPost()){
            //récupération des données envoyées par le formulaire
            $data = $this->getRequest()->getPost();

            //vérifie que les données répondent aux conditions des validateurs
            if($form->isValid($data)){
                $salt = hexdec(bin2hex(openssl_random_pseudo_bytes( 6 )));
                $passEnc = MD5($form->getValue('password') . $salt );
                //Zend_Debug::dump($passEnc);
                //création et initialisation d'un objet Default_Model_Entreprises
                //qui sera enregistré dans la base de données
                $entreprises = new Application_Model_Entreprises();
                $entreprises->setId((int)$form->getValue('id'));
                $entreprises->setName($form->getValue('name'));                
                
                $entreprises->save();
                
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
                $entreprises = new Application_Model_Entreprises();
                $entreprise = $entreprises->find($id);

                //assignation des valeurs de l'entrée dans un tableau
                //tableau utilisé pour la méthode populate() qui va remplir le champs du formulaire
                //avec les valeurs du tableau
                $data[] = array();
                $data['id'] = $entreprise->getId();
                $data['name'] = $entreprise->getName();
               
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
            $entreprises = new Application_Model_Entreprises();
            //appel de la fcontion de suppression avec en argument,
            //la clause where qui sera appliquée
            $result = $entreprises->delete("id=$id");

            //redirection
            $this->_helper->redirector('index');
        }
        else{
            $this->view->form = 'Impossible delete: id missing !';
        }
    }
}