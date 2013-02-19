<?php
class UsersController extends Zend_Controller_Action{
    //action par défaut
    public function indexAction(){
        //création d'un d'une instance Default_Model_Users
        $users = new Application_Model_Users();        
        
        //$this->view permet d'accéder à la vue qui sera utilisée par l'action
        //on initialise la valeur usersArray de la vue
        //(cf. application/views/scripts/users/index.phtml)
        //la valeur correspond à un tableau d'objets de type Application_Model_Users récupérés par la méthode fetchAll()
        $this->view->usersArray = $users->fetchAll();
        
        $roles = new Application_Model_roles();
        $this->view->rolesArray = $roles->fetchAll();
    }

    public function createAction(){
        //création du fomulaire
        $form = new Application_Form_Users();
        //indique l'action qui va traiter le formulaire
        $form->setAction($this->view->url(array('controller' => 'users', 'action' => 'create'), 'default', true));
        $form->submit->setLabel('Create');

        //assigne le formulaire à la vue
        $this->view->form = $form;
        
        $usersDb = new Application_Model_Users();
        $roles = $usersDb->role;
        $this->view->usersDb = $usersDb;

        Zend_Debug::dump($usersDb);

        //si la page est POSTée = formulaire envoyé
        if($this->_request->isPost()){
            //récupération des données envoyées par le formulaire
            $data = $this->_request->getPost();
            //vérifie que les données répondent aux conditions des validateurs
            if($form->isValid($data)){
                //création et initialisation d'un objet Default_Model_Users
                //qui sera enregistré dans la base de données
                
                //Pour le mot de passe on securise pas MD5 + concatenation d'un nombre au hazard
                $salt = hexdec(bin2hex(openssl_random_pseudo_bytes( 6 )));                
                $passEnc = MD5($form->getValue('password') . $salt );
                          
                $users = new Application_Model_Users();
                $users->setFirstname($form->getValue('firstname'));
                $users->setLastname($form->getValue('lastname'));
                $users->setMail($form->getValue('mail'));
                $users->setUsername($form->getValue('username'));
                $users->setPassword($passEnc);
                $users->setSalt($salt);
                $users->setRole($form->getValue('role'));
                $users->setRolesId($form->getValue('role_id'));

                $users->save();

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
        $form = new Application_Form_Users();
        //indique l'action qui va traiter le formulaire
        $form->setAction($this->view->url(array('controller' => 'users', 'action' => 'edit'), 'default', true));
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
                Zend_Debug::dump($passEnc);
                //création et initialisation d'un objet Default_Model_Users
                //qui sera enregistré dans la base de données
                $users = new Application_Model_Users();
                $users->setId((int)$form->getValue('id'));
                $users->setFirstname($form->getValue('firstname'));
                $users->setLastname($form->getValue('lastname'));
                $users->setMail($form->getValue('mail'));
                $users->setUsername($form->getValue('username'));
                $users->setPassword($passEnc);
                $users->setSalt($salt);
                $users->setRole($form->getValue('role'));
                $users->setRolesId($form->getValue('role_id'));
                
                $users->save();
                
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
                $users = new Application_Model_Users();
                $user = $users->find($id);

                //assignation des valeurs de l'entrée dans un tableau
                //tableau utilisé pour la méthode populate() qui va remplir le champs du formulaire
                //avec les valeurs du tableau
                $data[] = array();
                $data['id'] = $user->getId();
                $data['firstname'] = $user->getFirstname();
                $data['lastname'] = $user->getLastname();
                $data['mail'] = $user->getMail();
                $data['password'] = $user->getPassword();
                $data['role'] = $user->getRole();
                $data['roleId'] = $user->getRolesId(); /**/
                
                $data['roleName'] = $user->getRoleName($user->getRolesId());         
                
                
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
            $users = new Application_Model_Users();
            //appel de la fcontion de suppression avec en argument,
            //la clause where qui sera appliquée
            $result = $users->delete("user_id=$id");

            //redirection
            $this->_helper->redirector('index');
        }
        else{
            $this->view->form = 'Impossible delete: id missing !';
        }
    }
}