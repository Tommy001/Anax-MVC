<?php
namespace Anax\Comment;
 
/**
 * Model for Comments.
 *
 */
class Comments extends \Anax\MVC\CDatabaseModel {

    /**
     * Add a new comment.
     *
     * @param array $comment with all details.
     * 
     * @return void
     */
    public function add($comment)
    {
        $this->save($comment);
    }



    /**
     * Find and return all comments.
     *
     * @return array with all comments.
     */
    public function findAllComments($page) {

        $this->db->select()
             ->from($this->getSource())
             ->where("page = ?")   
             ->orderBy("id desc");
        $this->db->execute([$page]);  
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();

    }

    /**
     * Delete all comments with a specific Page.
     *
     * @return void
     */
    
    public function deleteAll($page = null)
    {
        $comments = $this->findAllComments($page);
        foreach($comments as $comment) {
            $this->delete($comment->id);
        }

    }

    /**
    *Deletes a post with a specific page and Id
    **/

    public function deletePost($id, $page){

         $comments = $this->findAllComments($page);
         foreach($comments as $comment){
             if($comment->id == $id){
                 $this->delete($id);
             }
         }
    }

    // ta fram en specifik kommentar från rätt page
    public function findComment($id, $page){ 
            $res = $this->query()
                ->where("id = ?")  
                ->andWhere("page = ?")
                ->execute([$id, $page]);  
            return $res;
    }
        


    // spara en kommentar efter ändring
    public function saveComment($id, $comment, $page){
           $comment['page'] = $page;
           $this->save($comment);
    }
    

}


