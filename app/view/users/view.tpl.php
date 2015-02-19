<h1><?=$title?></h1>
 
<?php

    $info = $user->getProperties();

    $status = "";
    if($info['active'] == null && $info['deleted'] == null) {
        $url = $this->url->create('users/activate/' . $info['id']);
        $status = 'Inaktiv | <a href="'.$url.'">Aktivera</a>';
    } else if( $info['deleted'] != null ) {
        $url = $this->url->create('users/restore/' . $info['id']);
        $status = 'Borttagen (' . $info['deleted'] . ') | <a href="'.$url.'">Återställ</a>';
    } else {
        $url = $this->url->create('users/deactivate/' . $info['id']);
        $status = 'Aktiv (' . $info['active'] . ') | <a href="'.$url.'">Inaktivera</a>';
    }

    $name = isset($info['name']) ? $info['name'] : null; 
    $acronym = isset($info['acronym']) ? $info['acronym'] : null;     
    $email = isset($info['email']) ? $info['email'] : null; 
    $phone = isset($info['phone']) ? $info['phone'] : null; 
?>


<h2>#<?= $info['id'] ?> <?= $info['name'] ?></h2>
<article>
<ul>
    <li>Användarnamn: <?= $acronym ?></li>
    <li>Namn: <?= $name ?></li>
<li>Status: <?= $status ?></li>
    <li>E-post: <?= $email ?></li>
    <li>Skapad: <?= $info['created'] ?></li>
</ul>
<p><a href='<?=$this->url->create('list')?>'><i class="fa fa-eye"></i> Visa alla</a></p> 
</article>
