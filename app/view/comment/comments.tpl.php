<?php if (is_array($comments)) : ?>

    <?php foreach ($comments as $id => $comment) : ?>

        <?php $gravatar = get_gravatar($comment['mail']); ?>
        <table class='post'>  
        <col width="13%">
        <col width="87%">
            <tr>
                <td>
                    <?=$gravatar?>
                </td>
                <td>
                    <div class='kontakt'>
                        <strong><a href='<?=$this->url->create('comment/edit') . '?id=' . $id . '&amp;key=' . $key?>'>ID: <?=$id+1?></a></strong>
                        <strong><?=$comment['name']?></strong>
                        <span>För <?=getTimeAgo($comment['timestamp']); ?> sedan</span>                        
                        <strong>E-post: </strong><?=$comment['mail']?>
                        <strong>Webbsida: </strong><?=$comment['web']?>
                    </div>    
                    <p><?=$comment['content']?></p>
                    <form method='post'>
                        <input type='hidden' name="redirect" value="<?=$this->url->create($this->request->getCurrentUrl())?>#comment">
                        <input type='hidden' name='key' value='<?= $key ?>'>
                        <input type='submit' name='edit' value='Ändra' onClick="this.form.action = '<?=$this->url->create('comment/edit/'.$id)?>'"/>
                        <input type='submit' name='delete' value='Ta bort' onClick="this.form.action = '<?=$this->url->create('comment/delete/'.$id)?>'"/>
                    </form> 
                </td>    
            </tr>
        </table>
    <?php endforeach; ?>
<?php endif; ?>
<a name='comment'></a>