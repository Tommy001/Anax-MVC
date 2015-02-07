<script type"text/javascript">
</script>
<div class='comment-form'>
    <form method=post>
        <input type=hidden name="key" value="<?= $key ?>">
        <input type=hidden name="redirect" value="<?= $_SERVER['HTTP_REFERER']?>#comment">
        <h2><a name='comment'>Ändra inlägg</a></h2>
        <div id='bottom' class='kontakt'>
            <textarea placeholder='Lämna en kommentar' cols='120' name='content'><?=$content?></textarea><br>
            <br><input placeholder='Namn' type='text' name='name' value='<?=$name?>'/>
            <input placeholder='Webbsida' type='text' name='web' value='<?=$web?>'/>
            <input placeholder='E-post' type='text' name='mail' value='<?=$mail?>'/><br>
            <p class=buttons>
                <input type='submit' name='save' value='Spara' onClick="this.form.action = '<?=$this->url->create('comment/save/'.$id)?>'"/>
                <input type='submit' name='cancel' value='Avbryt' onClick="this.form.action = '<?=$this->url->create('comment/cancel')?>'"/>
            </p>
        </div>    
    </form>
</div>
<script type="text/javascript"><!--
// November 3, 2009, http://www.willmaster.com/
// Copyright 2009 Bontrager Connection, LLC
function AutoScrollOnload() {

// Specify how many pixels from the left 
//    and how many down from the top to 
//    automatically scroll the page.

var InFromLeft = 0;
var DownFromTop = 1000;

// No other customization required.

window.scrollTo(InFromLeft,DownFromTop);
}
function AddOnloadEvent(f) {
if(typeof window.onload != 'function') { window.onload = f; }
else {
   var cache = window.onload;
   window.onload = function() {
      if(cache) { cache(); }
      f();
      };
   }
}
AddOnloadEvent(AutoScrollOnload);
//--></script>


