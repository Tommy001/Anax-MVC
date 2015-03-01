<?php
namespace Anax\Mymodule;
 
/**
 * Model for Upload.
 *
 */
class Upload extends \Anax\MVC\CDatabaseModel {

    /**
     * Add a new comment.
     *
     * @param array $comment with all details.
     * 
     * @return void
     */
    public function add($upload) {
        $this->save($upload);
    }

    public function findLast()
    {

      $this->db->select()
               ->from($this->getSource())
               ->where("id = ?");

      $this->db->execute([$this->id]);
      return $this->db->fetchInto($this);
    }  


}


