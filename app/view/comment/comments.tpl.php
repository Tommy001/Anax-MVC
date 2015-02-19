<div id='bottom2' class='kontakt'>

    <?php foreach ($comments as $val) : ?>
    <?php $comment = $val->getProperties(); ?>

        <?php $gravatar = get_gravatar($comment['mail']); ?>
        <table class='post'>  
            <colgroup>
                <col class="column-one">
                <col class="column-two">
            </colgroup>
            <tbody>
                <tr>
                    <td>
                        <?=$gravatar?>
                        </td>
                    <td>
                        <div class='kontakt'>
                            <strong><a href='<?=$this->url->create('comment/edit') . '?id=' . $comment['id'] . '&amp;page=' . $page?>'>ID: <?=$comment['id']+1?></a></strong>
                            <strong><?=$comment['name']?></strong>
                            <span>För <?=getTimeAgo($comment['created']); ?> sedan</span>                        
                            <strong>E-post: </strong><?=$comment['mail']?>
                            <strong>Webbsida: </strong><?=$comment['web']?>
                        </div>    
                        <p><?=$comment['content']?></p>
                        <form method='post'>
                            <input type='hidden' name="redirect" value="<?=$this->url->create($this->request->getCurrentUrl())?>#comment">
                            <input type='hidden' name='page' value='<?= $page ?>'>
                            <input type='submit' name='edit' value='Ändra' onClick="this.form.action = '<?=$this->url->create('comment/edit/'.$comment['id'])?>'"/>
                            <input type='submit' name='delete' value='Ta bort' onClick="this.form.action = '<?=$this->url->create('comment/delete/'.$comment['id'])?>'"/>
                        </form> 
                    </td>    
                </tr>
            </tbody>    
        </table>
    <?php endforeach; ?>

</div>
<a name='comment'></a>