<?php

namespace Phpmvc\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentsInDatabase implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;



    /**
     * Add a new comment.
     *
     * @param array $comment with all details.
     * 
     * @return void
     */
    public function add($comment, $key=null)
    {
        $comments = $this->session->get('comments', []);
        $comments[$key][] = $comment;
        $this->session->set('comments', $comments);
    }



    /**
     * Find and return all comments.
     *
     * @return array with all comments.
     */
    public function findAll($key = null)
    {

        $comments =  $this->session->get('comments', []);
        if(isset($comments[$key])){
            return $comments[$key];

        }
    }
    
    public function findAllComments(){
        $comments =  $this->session->get('comments', []);

        return $comments;
    }    


    /**
     * Delete all comments with a specific Key.
     *
     * @return void
     */
    public function deleteAll($key = null)
    {
        $comments = $this->findAllComments();
        unset($comments[$key]);

        $this->session->set('comments', $comments);
    }

    /**
    *Deletes a post with a specific key and Id
    **/
    public function deletePost($id, $key){

         $comments = $this->findAllComments();
        unset($comments[$key][$id]);

        $this->session->set('comments', $comments);
    }

    // ta fram en kommentar med $id och $key
    public function findComment($id, $key){
        $comment = $this->findAllComments();
        return $comment[$key][$id];
    }

    // spara en kommentar efter ändring
    public function save($id, $comment, $key){
         $comments = $this->findAllComments();
        $comments[$key][$id] = $comment;
        $this->session->set('comments', $comments);
    }
    
    // spara den aktuella sidans innehåll
    public function saveCurrentContent($content, $byline){
      
        $this->session->set('content', $content);
        $this->session->set('byline', $byline);
    }
    
    // hämta ut den aktuella sidans innehåll för att kunna visa den
    // inifrån klassen (utan tillgång till frontkontrollern)
    public function getCurrentContent(){
        $page[0] =  $this->session->get('content');
        $page[1] =  $this->session->get('byline');        
        return $page;
    }     
}
