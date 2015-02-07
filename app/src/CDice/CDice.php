<?php
/**
 * A CDice class to play around with a dice.
 *
 */
namespace Mos\CDice;
class CDice {
    
    /**
    * Properties
    *
    */
    protected $faces;
    public $rolls = array(6); 
    public $last;
    public $total = 0;
    public $roll=0;

 
    /**
    * Constructor
    *
    * @param int $faces the number of faces to use.
    */
  public function __construct($faces=6) {
    $this->faces = $faces;
    $total = 0;
    $roll = false;
  }    
    
    /**
    * Destructor
    */
    public function __destruct() {
    }     

    public function GetFaces() {
        return $this->faces;
    }


    /**
    * Get the rolls as an array.
    *
    */
    public function GetRollsAsArray() {
        return $this->rolls;
    }    

    /**
    * Roll the dice
    *
    */
    public function Roll($times) {
        $this->rolls = array();
 
        for($i = 0; $i < $times; $i++) {
            $last = rand(1, $this->faces);
            $this->rolls[] = $last;
            $this->total += $last;
        }
        $this->roll++;
        return $last;
    }
  
    /**
    * Get the total from the last roll(s).
    *
    */
    public function GetTotal() {
        return array_sum($this->rolls);
    }

  
    public function GetAverage() {
        return array_sum($this->rolls) / count($this->rolls, 1);
    }
    
    public function GetLastRoll() {
        return $this->last;
    }

    
}

