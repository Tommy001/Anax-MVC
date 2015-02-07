<?php
/**
 * A dice with images as graphical representation.
 *
 */
namespace Mos\CDice;
class CDiceGameRound extends CDiceGameBoard {
    
  // Properties and methods extending or overriding base class
  public $round;
  public $reset;
  public $save;
  public $allround = 0;
  public $reach100 = false;
  /**
    * Constructor
    *
    */
  public function __construct($faces = 6) { 
    $this->faces = $faces;
    $total = 0;
    $roll = false;
    $reach100 = false;
  }   
 

  
    public function SaveRound() {
        $this->allround += $this->total; // nya omgången adderas till den gamla
        $this->total = 0; // omgången nollas och börjar från början
        $html = $this->GetGameBoard();
        return $html;
    }
    
    public function ResetRound() {
        $this->total = 0; // nollställ omgången

    }   
    public function IfRollDice() {
        $last = $this->Roll(1); // kasta och få tillbaka senaste kastet
        $round = $this->GetTotalRound(); // få tillbaka omgångens totalsumma
        $reset = $last==1 ? true : false; // true om det senaste kastet är 1
        if ($reset) {
            $this->ResetRound(); // nollställ omgången
        } 
        $html = $this->GetGameBoard(); // visa tärningen    
        return $html;
    }
    
    public function GetTotalRound() { 
        return $this->total;
    }

    public function GetTotalAllRounds() {
        return $this->allround;
    }
    
    public function Reach100() {
        $reach100 = false;
        if($this->allround >= 100) {
            $reach100 = true;
        }
        return $reach100;
    }    
}    
