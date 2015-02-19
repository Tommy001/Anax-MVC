<?php

namespace Anax\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    
    protected $db;
    protected $comments;
    
    /**
 * Initialize the controller.
 *
 * @return void
 */
    public function initialize() {
        $this->comments = new \Anax\Comment\Comments();
        $this->comments->setDI($this->di);
    }
        
    public function createAddForm($page){
            $this->initialize();
            
            $formular = $this->form->create([], [
            'name' => [
                'class'       => 'contactfields',    
                'label'       => 'Namn:',                 
                'type'        => 'text',       
                'required'    => true,
                'validation'  => ['not_empty'],
                ],                 
            'mail' => [
                'class'       => 'contactfields', 
                'label'       => 'E-post:',                 
                'type'        => 'text',
                'required'    => true,
                'validation'  => ['not_empty', 'email_adress'],
                ],
            'web' => [
                'class'       => 'contactfields', 
                'label'       => 'Webbsida:',                 
                'type'        => 'text',                 
                'required'    => false,
                ],

            'content' => [
                'class'       => 'kontakt',      
                'label'       => 'Kommentar',                      
                'type'        => 'textarea',        
                'required'    => true,
                'validation'  => ['not_empty'],
                ],        
            'redirect' => [
                'type'        => 'hidden',
                'value'       => $this->url->create($this->request->getCurrentUrl()).'/'.$page.'#comment',               
                ],                 
            'Spara' => [
                'class'       => 'margin-above',                
                'type'      => 'submit',
                'callback'  => function ($formular) {
                    $this->form->saveInSession = false;
                    return true;
                }
            ],

        ]); 
            return $formular;
    }
    
    
    /**
     * View all comments.
     *
     * @return void
     */
    public function viewAction($page) {

        $this->initialize();  

        $formular = $this->createAddForm($page);
        $form = $this->form->getHTML();
        $status = $this->form->check();   
        if ($status === true) {
            $this->addAction($page);

        } else if ($status === false) {
            $this->form->AddOutput("<h3>Ett fel uppstod. Kontrollera felmeddelandena ovan.</h3>");
            $form = $this->form->getHTML();           
            
        }        
        
        $all = $this->comments->findAllComments($page);

        $this->views->add('comment/formdb', [
            'formular' => $form,
        ]);
        
        $this->views->add('comment/comments', [
            'comments' => $all,
            'page' => $page,
        ]);        
    }


    /**
     * Add a comment.
     *
     * @return void
     */
    public function addAction($page) {

        $this->initialize();  
        $now = date('Y-m-d H:i:s');        
        $this->comments->add([
            'mail' => $this->form->value('mail'),
            'web' => $this->form->value('web'),
            'name' => $this->form->value('name'),
            'content' => $this->form->value('content'),
            'page' => $page,
            'created' => $now,
            'ip' => 'men det här kommer väl med',
        ]);  


         //   $this->response->redirect($this->request->getPost('redirect'));
       
         return;
    }



    /**
     * Remove all comments.
     *
     * @return void
     */
    public function removeAllAction() {
        $this->initialize();  
        $isPosted = $this->request->getPost('doRemoveAll');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $page = $this->request->getPost('page');

        $this->comments->deleteAll($page);

        $this->response->redirect($this->request->getPost('redirect'));
    }
    
    /**
     * Remove 1 comment.
     *
     * @return void
     */    
    public function deleteAction($id){
        $this->initialize(); 
        $isPosted = $this->request->getPost('delete');

        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $page = $this->request->getPost('page');

        $this->comments->deletePost($id, $page);

        $this->response->redirect($this->request->getPost('redirect'));
    }
    
    // försök till funktion som sparar innehållet i aktuell sida i sesssionen
    public function saveCurrentAction($content, $byline) {
        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);
        $comments->saveCurrentContent($content, $byline);
    }
    
    public function getCurrentAction() {
        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);
        $page = $comments->getCurrentContent();
        return $page;
    }
            
        
    
    // kan anropas med både knappar och länkar, post och get
      public function editAction($id=null) {
        $this->initialize(); 
        
        if(!isset($id)) {
            $id = $this->request->getGet('id');
        }
        
        $page = $this->request->getPost('page');
        
        if(!isset($page)) {
            $page = $this->request->getGet('page');
        }   

        $comment = $this->comments->findComment($id, $page);

        $this->theme->setTitle('Ändra kommentar');

        
// hämtar den aktuella sidans huvudinnehåll från sessionsvariabel
        $maincontent = $this->getCurrentAction();

// visar den aktuella sidan överst, följt av ev. byline 
// hmm får lägga till tärningsspelets stilmall också
// kanske bättre att lägga alla stilmallar i config?
        $this->theme->addStylesheet('css/dicegame.css');
        $this->theme->setVariable('wrapperclass', 'typography');        
        $this->views->add("me/$page", [        
        'content' => $maincontent[0],
        'byline' => $maincontent[1],        
    ]);        

        $this->views->add("comment/edit", [
            'mail' => $comment[0]->mail,
            'web'       => $comment[0]->web, 
            'name'      => $comment[0]->name, 
            'content'   => $comment[0]->content, 
            'id'    => $id, 
            'page' => $page,
        ]);


    }

    public function CancelAction(){
        $this->response->redirect($this->request->getPost('redirect'));
    }

    public function SaveAction($id){
        $this->initialize(); 
        $isPosted = $this->request->getPost('save');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comment = [
            'content'   => $this->request->getPost('content'),
            'name'      => $this->request->getPost('name'),
            'web'       => $this->request->getPost('web'),
            'mail'      => $this->request->getPost('mail'),
            'updated'   => date('Y-m-d H:i:s'),
            'ip'        => $this->request->getServer('REMOTE_ADDR'),
            'id'        => $id,
            'page'        => null,
        ];

        $page = $this->request->getPost('page'); ///Gets the page

        $this->comments->saveComment($id, $comment, $page);

        $this->response->redirect($this->request->getPost('redirect'));


    }    
    
}
