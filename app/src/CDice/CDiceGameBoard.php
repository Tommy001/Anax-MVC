<?php
/**
 * A dice with images as graphical representation.
 *
 */
namespace Mos\CDice;
class CDiceGameBoard extends CDice {
 
  // Properties and methods extending or overriding base class

    const FACES = 6;  
    /**
     * Constructor
     *
     */
    public function __construct() {
         parent::__construct(self::FACES);
    }
      /**
   * Hämta spelplanen.
   *
   */
    public function GetGameBoard() {
        $clickable = true;
        if($this->Reach100()) {$clickable = false;}
        $html = "<div id='dice-container'>";
        $html .= "<div class='dicegame'>";
        if($clickable){$html .= "<a href='?roll'>";}
        $html .= "<ul class='dice'>";
        foreach($this->rolls as $val) {
            $html .= "<li class='dice-$val'></li></ul>";
        }
        if($clickable){$html .= "</a>";}
        $html .= "</div><div class='diceround'>
        <h2>".$this->total."<br>poäng</h2>
        </div>
        <a href='?save'><div class='dicesave'>
        <h1><br>".$this->allround."</h1>
        </div></a>
        <div class='diceroll'>
        <h2>".$this->roll."<br>kast</h2>
        </div>
        <a href='?init'><div class='diceinit'>
        </div></a>
        ";
        if($this->Reach100()) {
            $html .= "<div class='reach100'>
        <h3 class='blink'><br>Du nådde 100 på ".$this->roll." kast.</h3>
        </div></div>";
         }
         $html .= "</div>"; // avslutar wrapper
        return $html;
    }

  
 
   
}
