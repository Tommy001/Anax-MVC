<h1><?=$title?></h1>

<article class='article1'><table>

<?php foreach ($users as $user) {


$info = $user->getProperties();

$url = $this->url->create('users/id/' . $info['id']);
$url_update = $this->url->create('users/update/' . $info['id']);
$url_delete = $this->url->create('users/softDelete/' . $info['id']);
$url_desactivate = $this->url->create('users/deactivate/' . $info['id']);
$url_activate = $this->url->create('users/activate/' . $info['id']);
$status = "";
if( $info['active'] == null && $info['deleted'] == null) {
    $status = '<a href="'.$url_activate.'">Inaktiv</a>';
} else if( $info['deleted'] != null) {
    $status = 'Borttagen';
} else {
    $status = '<a href="'.$url_desactivate.'">Aktiv</a>';
}


echo <<<EOD
    <tr>
        <td><a href="$url">{$info['id']}</a></td>
        <td>{$info['acronym']}</td>
        <td>{$info['name']}</td>
        <td>{$info['email']}</td>
        <td>$status</td>
        <td><a href="$url"><i class="fa fa-eye"></i>
</a></td>
        <td><a href="$url_update"><i class="fa fa-pencil"></i>
</a></td>
        <td><a href="$url_delete"><i class="fa fa-trash-o"></i>
</a></td>        
    </tr>
EOD;

} ?>
</table></article>
 
<p><a href='<?=$this->url->create('users/add')?>'><i class="fa fa-plus-circle"></i> Lägg till användare</a></p> 
