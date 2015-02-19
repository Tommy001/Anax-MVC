<?php

namespace Phpmvc\Comment;

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
        $this->comments = new \Phpmvc\Comment\Comments();
        $this->comments->setDI($this->di);
    }
        

    /**
     * View all comments.
     *
     * @return void
     */
    public function viewAction($page) {
        $this->initialize();  

        $all = $this->comments->findAllComments($page);

    $this->views->add('comment/form', [
        'mail'      => null,
        'web'       => null,
        'name'      => null,
        'content'   => null,
        'output'    => null,
        'page'    => $page, 
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
    public function addAction() {
        $this->initialize();  
        $isPosted = $this->request->getPost('doCreate');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comment = [
            'content'   => $this->request->getPost('content'),
            'name'      => $this->request->getPost('name'),
            'web'       => $this->request->getPost('web'),
            'mail'      => $this->request->getPost('mail'),
            'created' =>   date('Y-m-d H:i:s'),
            'page'      => null,
            'ip'        => $this->request->getServer('REMOTE_ADDR'),
        ];

        $page = $this->request->getPost('page'); // hämta page, skickas som hidden value från formuläret


        $this->comments->add($comment, $page);

        $this->response->redirect($this->request->getPost('redirect'));
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
