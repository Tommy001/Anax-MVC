<?php
namespace Anax\Users;
 
/**
 * Model for Users.
 *
 */
class User extends \Anax\MVC\CDatabaseModel
{
    /**
    * Find and return specific.
    * Från rad 70 i CDabaseModel, $id är ersatt med $acronym
    * 
    * @return this
    */
    public function findAcronym($acronym)
    {
      $this->db->select()
               ->from($this->getSource())
               ->where("acronym = ?");

      $this->db->execute([$acronym]);
      return $this->db->fetchInto($this);
    }    

}


