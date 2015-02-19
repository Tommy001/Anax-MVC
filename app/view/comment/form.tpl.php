<br><br>
<h2><a name='comment'>Kommentarer</a></h2>
<div class='comment-form'>
    <form method=post>
        <input type='hidden' name='redirect' value='<?=$this->url->create($this->request->getCurrentUrl())?>#comment'>
        <input type='hidden' name='page' value='<?= $page ?>'>  
        <div id='bottom' class='kontakt'>
            <p class='toggler'>
                <textarea placeholder='Lämna en kommentar' class='kontakt' name='content'><?=$content?></textarea>
                </p>
                <div class='kontakt'>
                    <br><input placeholder='Namn' type='text' name='name' value='<?=$name?>'/>
                    <input placeholder='Webbsida' type='text' name='web' value='<?=$web?>'/>
                    <input placeholder='E-post' type='text' name='mail' value='<?=$mail?>'/><br>
                    <p class=buttons>
                        <input type='submit' name='doCreate' value='Spara' onClick="this.form.action = '<?=$this->url->create('comment/add')?>'"/>
                        <input type='reset' value='Rensa'/>
                        <input type='submit' name='doRemoveAll' value='Ta bort alla' onClick="this.form.action = '<?=$this->url->create('comment/remove-all')?>'"/>  
                    </p>
              </div>      
        </div>
    </form>
</div>    

