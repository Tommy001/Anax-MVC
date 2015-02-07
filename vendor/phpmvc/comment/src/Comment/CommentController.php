<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;



    /**
     * View all comments.
     *
     * @return void
     */
    public function viewAction($key=null) {
        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $all = $comments->findAll($key);
        
    $this->views->add('comment/form', [
        'mail'      => null,
        'web'       => null,
        'name'      => null,
        'content'   => null,
        'output'    => null,
        'key'    => $key, 
    ]);        

        $this->views->add('comment/comments', [
            'comments' => $all,
            'key' => $key,
        ]);
    }



    /**
     * Add a comment.
     *
     * @return void
     */
    public function addAction()
    {
        $isPosted = $this->request->getPost('doCreate');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comment = [
            'content'   => $this->request->getPost('content'),
            'name'      => $this->request->getPost('name'),
            'web'       => $this->request->getPost('web'),
            'mail'      => $this->request->getPost('mail'),
            'timestamp' => time(),
            'ip'        => $this->request->getServer('REMOTE_ADDR'),
        ];

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);
        $key = $this->request->getPost('key'); // hämta key, skickas som hidden value från formuläret
        $comments->add($comment, $key);

        $this->response->redirect($this->request->getPost('redirect'));
    }



    /**
     * Remove all comments.
     *
     * @return void
     */
    public function removeAllAction()
    {
        $isPosted = $this->request->getPost('doRemoveAll');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);
        $key = $this->request->getPost('key');
        $comments->deleteAll($key);

        $this->response->redirect($this->request->getPost('redirect'));
    }
    
    public function deleteAction($id){

        $isPosted = $this->request->getPost('delete');

        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $key = $this->request->getPost('key');

        $comments->deletePost($id, $key);

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
        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);
        
        if(!isset($id)) {
            $id = $this->request->getGet('id');
        }
        
        $key = $this->request->getPost('key');
        
        if(!isset($key)) {
            $key = $this->request->getGet('key');
        }        

        $comment = $comments->findComment($id, $key);

        $this->theme->setTitle('Ändra kommentar');
        
        
// hämtar den aktuella sidans innehåll från sessionsvariabel
        $page = $this->getCurrentAction();

// visar den aktuella sidan överst, följt av ev. byline 
// hmm får lägga till tärningsspelets stilmall också
// kanske bättre att lägga alla stilmallar i config?
        $this->theme->addStylesheet('css/dicegame.css');
        $this->views->add("me/$key", [        
        'content' => $page[0],
        'byline' => $page[1],        
    ]);        

        $this->views->add("comment/edit", [
            'mail' => $comment['mail'],
            'web'       => $comment['web'], 
            'name'      => $comment['name'], 
            'content'   => $comment['content'], 
            'id'    => $id, 
            'key' => $key,
        ]);


    }

    public function CancelAction(){
        $this->response->redirect($this->request->getPost('redirect'));
    }

    public function SaveAction($id){

        $isPosted = $this->request->getPost('save');
        
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }



        $comment = [
            'content'   => $this->request->getPost('content'),
            'name'      => $this->request->getPost('name'),
            'web'       => $this->request->getPost('web'),
            'mail'      => $this->request->getPost('mail'),
            'timestamp' => time(),
            'ip'        => $this->request->getServer('REMOTE_ADDR'),
            'id'        => $id,
        ];

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $key = $this->request->getPost('key'); ///Gets the key

        $comments->save($id, $comment, $key);

        $this->response->redirect($this->request->getPost('redirect'));


    }    
    
}
