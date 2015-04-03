<?php
namespace Anax\Users;
 
/**
 * A controller for users and admin related events.
 *
 */
class UsersController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
 
    protected $users;
/**
 * Initialize the controller.
 *
 * @return void
 */
public function initialize()
{
    $this->users = new \Anax\Users\User();
    $this->users->setDI($this->di);
}

/**
 * List all users.
 *
 * @return void
 */
public function listAction()
{
 
    $all = $this->users->findAll();
 
    $this->theme->setTitle("Visa alla användare");
    $this->theme->setVariable('wrapperclass', 'typography');     
    $this->views->add('users/list-all', [
        'users' => $all,
        'title' => "Visa alla användare",
    ]);
    
    $trash = $this->trashcanAction();
    $this->views->addString("<h1>Papperskorgen</h1>" . $trash, 'sidebar');   
}

/**
 * List user with id.
 *
 * @param int $id of user to display
 *
 * @return void
 */
public function idAction($id = null)
{
    $this->theme->setVariable('wrapperclass', 'typography'); 
    $user = $this->users->find($id);
 
    $this->theme->setTitle("Visa användare med id");   
    $this->views->add('users/view', [
        'user' => $user,
        'title' => 'Visa en användare',
    ]);
}

/**
 * Add new user.
 *
 * @param string $acronym of user to add.
 *
 * @return void
 */
public function addAction() {
    $this->initialize();
    $formular = $this->form->create([], [
        'name' => [
            'type'        => 'text',
            'label'       => 'Namn:',
            'required'    => true,
            'validation'  => ['not_empty'],
        ],
        'acronym' => [
            'type'        => 'text',
            'label'       => 'Användarnamn:',
            'required'    => true,
            'validation'  => ['not_empty','custom_test' => 
                             ['message' => 'Det användarnamnet finns redan. Välj ett annat.',
                             'test' => array($this, 'addDuplicateAcronym')
                    ]
                    ]
              ],
        'email' => [
            'type'        => 'text',        
            'label'       => 'E-post:',            
            'required'    => true,
            'validation'  => ['not_empty', 'email_adress'],
        ],
        'password' => [
            'type'        => 'password',
            'label'       => 'Lösenord:',            
            'required'    => true,
            'validation'  => ['not_empty'],
        ],        
        'Spara' => [
            'type'      => 'submit',
            'callback'  => function ($formular) {
                $this->form->saveInSession = false;
                return true;
            }
        ],

    ]);   
    
        // Kolla rad 400 i CForm.. Check if a form was submitted and perform validation...
    $status = $this->form->check();

    if ($status === true) {

        $now = date('Y-m-d H:i:s');        
        $this->users->save([
            'acronym' => $this->form->value('acronym'),
            'email' => $this->form->value('email'),
            'name' => $this->form->value('name'),
            'password' => password_hash($this->form->value('password'), PASSWORD_DEFAULT),
            'created' => $now,
            'active' => $now,
        ]);

        $url = $this->url->create('users/id/' . $this->users->id);

// läs här om $this->response: http://dbwebb.se/kunskap/anvand-cform-tillsammans-med-anax-mvc
         $this->response->redirect($url);

        } else if ($status === false) {
            $this->form->AddOutput("<h3>Ett fel uppstod. Kontrollera felmeddelandena ovan.</h3>");
            header("Location: " . $this->di->request->getCurrentUrl());
        }
        
        $this->theme->setVariable('wrapperclass', 'typography');     
        $this->theme->setTitle('Lägg till användare');   
        $this->views->addString("<h1>Lägg till användare</h1>" . $this->form->getHTML(), 'main');  
        $list = $this->url->create('list');
        $this->views->addString("<p><a href='"."$list"."'><i class='fa fa-eye'></i> Visa alla</a></p>", "triptych-1");          
    }
    

    
/**
 * Update user.
 *
 * @param string $acronym of user to update.
 *
 * @return void
 */
public function updateAction($id = null) {
    $this->initialize();
    $user = $this->users->find($id);
    $info = $user->getProperties();
    $id = isset($info['id']) ? $info['id'] : null; 
    $name = isset($info['name']) ? $info['name'] : null; 
    $acronym = isset($info['acronym']) ? $info['acronym'] : null;     
    $email = isset($info['email']) ? $info['email'] : null;   

    $formular = $this->form->create([], [
        'name' => [
            'type'        => 'text',
            'label'       => 'Namn:',
            'value'       => $name,
            'required'    => true,
            'validation'  => ['not_empty'],
        ],
        'acronym' => [
            'type'        => 'text',
            'value'       => $acronym,
            'label'       => 'Användarnamn:',
            'required'    => true,
            'validation'  => ['not_empty']
              ],
        'email' => [
            'type'        => 'text',
            'value'       => $email,            
            'label'       => 'E-post:',            
            'required'    => true,
            'validation'  => ['not_empty', 'email_adress'],
        ],
        'password' => [
            'type'        => 'password',
            'label'       => 'Nytt lösenord:',            
            'required'    => false,
            'validation'  => ['pass'],
        ],        
        'Spara' => [
            'type'      => 'submit',
            'callback'  => function ($formular) {
                $this->form->saveInSession = false;
                return true;
            }
        ],

    ]);   
    
        // Kolla rad 400 i CForm.. Check if a form was submitted and perform validation...
    $status = $this->form->check();

    if ($status === true) {

        $active = date('Y-m-d H:i:s');        
        $updated = isset($id) ? date('Y-m-d H:i:s') : null;
        $created = !(isset($id)) ? date('Y-m-d H:i:s') : null;
        $this->users->save([
            'id' => $id,
            'acronym' => $this->form->value('acronym'),
            'email' => $this->form->value('email'),
            'name' => $this->form->value('name'),
            'password' => password_hash($this->form->value('password'), PASSWORD_DEFAULT),
            'created' => $created,
            'active' => $active,
            'updated' => $updated,
        ]);

        $url = $this->url->create('users/id/' . $this->users->id);

// läs här om $this->response: http://dbwebb.se/kunskap/anvand-cform-tillsammans-med-anax-mvc
         $this->response->redirect($url);

        } else if ($status === false) {
            $this->form->AddOutput("<h3>Ett fel uppstod. Kontrollera felmeddelandena ovan.</h3>");
            header("Location: " . $this->di->request->getCurrentUrl());
        }
        
        $this->theme->setVariable('wrapperclass', 'typography');     
        $this->theme->setTitle('Uppdatera användare');   
        $this->views->addString("<h1>Uppdatera användare</h1>" . $this->form->getHTML(), 'main');  
        $list = $this->url->create('list');
        $this->views->addString("<p><a href='"."$list"."'><i class='fa fa-eye'></i> Visa alla</a></p>", "triptych-1");          
    }
    
    public function updateDuplicateAcronym($acronym, $id) {
        $user = $this->users->findAcronym($acronym);
        
        return empty($user);

        return $duplicate;
}     
    public function addDuplicateAcronym($acronym) {
        $user = $this->users->findAcronym($acronym);
        return empty($user);
}    

/**
 * Delete user.
 *
 * @param integer $id of user to delete.
 *
 * @return void
 */
public function deleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
 
    $res = $this->users->delete($id);
 
    $url = $this->url->create('list');
    $this->response->redirect($url);
}

/**
 * Delete (soft) user.
 *
 * @param integer $id of user to delete.
 *
 * @return void
 */
public function softDeleteAction($id = null)
{
    if (!isset($id)) {
        die("Missing id");
    }
 
    $now = gmdate('Y-m-d H:i:s');
 
    $user = $this->users->find($id);
 
    $user->deleted = $now;
    $user->save();
 
    $url = $this->di->url->create($_SERVER['HTTP_REFERER']);
    $this->response->redirect($url);
}

/**
 * List all active and not deleted users.
 *
 * @return void
 */
public function activeAction() {   
    $all = $this->users->query()
        ->where('active IS NOT NULL')
        ->andWhere('deleted is NULL')
        ->execute();
 
    $this->theme->setTitle("Aktiva användare");
    $this->theme->setVariable('wrapperclass', 'typography');    
    $this->views->add('users/list-all', [
        'users' => $all,
        'title' => "Aktiva användare",
    ]);
    
    $trash = $this->trashcanAction();
    $this->views->addString("<h1>Papperskorgen</h1>" . $trash, 'sidebar');       
}

/**
 * List all active and not deleted users.
 *
 * @return void
 */
public function inactiveAction() {   
    $all = $this->users->query()
        ->where('active IS NULL')
        ->andWhere('deleted is NULL')
        ->execute();
 
    $this->theme->setTitle("Inaktiva användare");
    $this->theme->setVariable('wrapperclass', 'typography');    
    $this->views->add('users/list-all', [
        'users' => $all,
        'title' => "Inaktiva användare",
    ]);
    
    $trash = $this->trashcanAction();
    $this->views->addString("<h1>Papperskorgen</h1>" . $trash, 'sidebar');       
}

/**
 * deactivate a user.
 *
 * @return void
 */
public function deactivateAction($id=null) {      
        $this->users->save([
            'id'          => $id,
            'active'    => null,
        ]);
        $url = $this->di->url->create($_SERVER['HTTP_REFERER']);
        $this->response->redirect($url);
}

/**
 * activate a user.
 *
 * @return void
 */
public function activateAction($id=null) {   
        $now = date('Y-m-d H:i:s'); 
        $this->users->save([
            'id'          => $id,
            'active'    => $now,
        ]);
        $url = $this->di->url->create($_SERVER['HTTP_REFERER']);
        $this->response->redirect($url);
}

/**
 * List all soft deleted users.
 *
 * @return void
 */
public function trashcanAction() {
    $trash_user = "<article><table>";
    $all = $this->users->query()
        ->where('deleted is NOT NULL')
        ->execute();
    $url_delete_trash = $this->url->create('users/deleteTrash/'); 
    foreach($all as $user){
        $user = $user->getProperties();
        $url_restore = $this->url->create('users/restore/' . $user['id']);
        $url_empty = $this->url->create('users/delete/' . $user['id']);   
        $trash_user .= <<<EOD
        <tr>
        <td>{$user['id']}</td>
        <td>{$user['acronym']}</td>
        <td><a href="$url_restore"><i class="fa fa-undo"></i> Ångra
        </a></td>
        <td><a href="$url_empty"><i class="fa fa-minus-circle"></i>
        Ta bort</a></td>        
        </tr>
        
EOD;
    }
        $trash_user .= "</table></article>";
        $trash_user .= "<br><p><a href='"."$url_delete_trash"."'>Töm hela papperskorgen</a></p>";
        return $trash_user;
}

public function restoreAction($id=null) {
        $now = date('Y-m-d H:i:s');    
        $this->users->save([
            'id'          => $id,
            'deleted'    => null,
            
        ]);
        $url = $this->di->url->create($_SERVER['HTTP_REFERER']);
        $this->response->redirect($url);
}

public function deleteTrashAction($id=null) {
 
    $res = $this->users->query()
        ->where('deleted is NOT NULL')
        ->execute();
    if(isset($res)){     
        foreach($res as $id) {
            $this->users->delete($id->id);
        }
        $url = $this->di->url->create('users/list');
        $this->response->redirect($url);
    }
}


}
