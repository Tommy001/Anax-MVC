<?php 
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php';
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->theme->configure(ANAX_APP_PATH . 'config/theme_me.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');

$di->set('CommentController', function() use ($di) {
    $controller = new Anax\Comment\CommentController();
    $controller->setDI($di);
    return $controller;
});

$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/database_mysql.php');
    $db->connect();
    return $db;
});

$di->set('UsersController', function() use ($di) {
    $controller = new \Anax\Users\UsersController();
    $controller->setDI($di);
    return $controller;
});


// Set the title of the page
$app->theme->setVariable('title', "Min Me-sida");

 
$app->router->add('', function() use ($app) {
     $app->theme->setVariable('wrapperclass', 'typography'); 
     $app->theme->setTitle("Om mig");  
     $content = $app->fileContent->get('me.md');
     $byline  = $app->fileContent->get('byline.md');
     $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');     
     $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
     $app->views->add('me/page', [        
        'content' => $content,
        'byline' => $byline,
    ]);
     
    $app->theme->addStylesheet('css/comment.css');
    $app->theme->addJavaScript('js/toggle.js');

    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'view',
        'params'     => ['page',],        
    ]);

    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'saveCurrent',
        'params'     => [$content,$byline,],        
    ]);

     
});
 
$app->router->add('redovisning', function() use ($app) {
    $app->theme->setVariable('wrapperclass', 'typography'); 
    $app->theme->setTitle("Redovisning");
    $content = $app->fileContent->get('redovisning.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
    $byline  = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');   
 
    $app->views->add('me/redovisning', [
        'content' => $content,
        'byline' => $byline,
    ]);
    
        
    $app->theme->addStylesheet('css/comment.css');
    $app->theme->addJavaScript('js/toggle.js');

    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'view',
        'params'     => ['redovisning',],        
    ]);

    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'saveCurrent',
        'params'     => [$content,$byline,],        
    ]);    
       
 
});

$app->router->add('dicegame', function() use ($app) {
    $app->theme->setVariable('wrapperclass', 'typography'); 
    $app->theme->setTitle("Tärningsspel");
    $app->theme->addStylesheet('css/dicegame.css');
    $app->theme->addJavaScript('js/toggle.js');
    
    $content = null;
    $last = 0;
    $round = 0;
    $roll = $app->request->getGet('roll', null);
    $save = $app->request->getGet('save', null);
    $init = $app->request->getGet('init', null);    
    
    if(isset($init)) {
        unset($_SESSION['play100']);
    }

    if(isset($_SESSION['play100'])) {
        $play100 = $_SESSION['play100'];
    } else {
        $play100 = new \Mos\CDice\CDiceGameRound();
        $_SESSION['play100'] = $play100;
    }

    $content = $play100->GetGameBoard(); // visa spelplanen

    if (isset($roll) && !$play100->Reach100()) { // tillåt kast om poängen < 100
        $content = $play100->IfRollDice();
    }

    if (isset($save)) { // efter klick på Spara: 
        $content = $play100->SaveRound($round); // spara och få tillbaka spelplanen
    }


    $app->views->add('me/dicegame', [
        'content'      => $content,
    ]);
    
        
    $app->theme->addStylesheet('css/comment.css');

    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'view',
        'params'     => ['dicegame',],        
    ]);

    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'saveCurrent',
        'params'     => [$content,$byline=null,],        
    ]);       
      

});

$app->router->add('theme', function() use ($app) {

     $app->theme->setVariable('wrapperclass', 'regioner');      
     $app->theme->setTitle("Mitt tema");  
     $content = $app->fileContent->get('tema.md');   
     $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
     $sidebar = $app->fileContent->get('sidebar.md');   
     $sidebar = $app->textFilter->doFilter($sidebar, 'shortcode, markdown'); 
     $flash = $app->fileContent->get('flash.md');   
     $flash = $app->textFilter->doFilter($flash, 'shortcode, markdown'); 
     
     $app->views->add('theme/page', [        
        'content' => $content,
    ]);
    $app->views->addString($sidebar, 'sidebar');  
    $app->views->addString($flash, 'flash');  
});

$app->router->add('regioner', function() use ($app) {

     $app->theme->setTitle("Regioner");  
     $app->theme->setVariable('wrapperclass', 'regioner');         
     $app->views->addString('flash', 'flash')
               ->addString('featured-1', 'featured-1')
               ->addString('featured-2', 'featured-2')
               ->addString('featured-3', 'featured-3')
               ->addString('main', 'main')
               ->addString('sidebar', 'sidebar')
               ->addString('triptych-1', 'triptych-1')
               ->addString('triptych-2', 'triptych-2')
               ->addString('triptych-3', 'triptych-3')
               ->addString('footer-col-1', 'footer-col-1')
               ->addString('footer-col-2', 'footer-col-2')
               ->addString('footer-col-3', 'footer-col-3')
               ->addString('footer-col-4', 'footer-col-4');
 
});

$app->router->add('grid', function() use ($app) {

     $app->theme->setTitle("Rutnät");  
     $app->theme->setVariable('wrapperclass', 'rutor');        
     $content = $app->fileContent->get('rutor.md');   
     $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
     $app->views->add('theme/page', [        
        'content' => $content,
    ]);     

 
});

$app->router->add('typography', function() use ($app) {

     $app->theme->setVariable('wrapperclass', 'typografi');    
     $app->theme->setTitle("Typografi");  
     $content = $app->fileContent->get('typography.html');   

     $app->views->add('theme/page', [        
        'content' => $content,
    ]);     
    $app->views->addString($content, 'sidebar');
 
});

$app->router->add('fontawesome', function() use ($app) {

     $app->theme->setTitle("Font Awesome");  
     $app->theme->setVariable('wrapperclass', 'typsnitt');
     $content = $app->fileContent->get('fontawesome.html');   
     $sidebar = $app->fileContent->get('variationer.html');   

     $app->views->add('theme/font', [        
        'content' => $content,
    ]);     
    $app->views->addString($sidebar, 'sidebar');
 
});


$app->router->add('setup', function() use ($app) {
    $app->theme->setVariable('wrapperclass', 'typography');     
    $app->theme->setTitle("Användare");          
  //  $app->db->setVerbose();
 
    $app->db->dropTableIfExists('user')->execute();
 
    $app->db->createTable(
        'user',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'acronym' => ['varchar(20)', 'unique', 'not null'],
            'email' => ['varchar(80)'],
            'name' => ['varchar(80)'],
            'password' => ['varchar(255)'],
            'created' => ['datetime'],
            'updated' => ['datetime'],
            'deleted' => ['datetime'],
            'active' => ['datetime'],
        ]
    )->execute();
    
        $app->db->insert(
        'user',
        ['acronym', 'email', 'name', 'password', 'created', 'active']
    );
 
    $now = gmdate('Y-m-d H:i:s');
 
    $app->db->execute([
        'admin',
        'admin@dbwebb.se',
        'Administrator',
        password_hash('admin', PASSWORD_DEFAULT),
        $now,
        $now
    ]);
 
    $app->db->execute([
        'doe',
        'doe@dbwebb.se',
        'John/Jane Doe',
        password_hash('doe', PASSWORD_DEFAULT),
        $now,
        $now
    ]);
    $app->db->execute([
        'tompa',
        'tommy@franskaord.se',
        'Tommy Johansson',
        password_hash('tompa', PASSWORD_DEFAULT),
        $now,
        $now
    ]);    
    $app->db->execute([
        'kalle',
        'kalle@telia.se',
        'Kalle Karlsson',
        password_hash('tompa', PASSWORD_DEFAULT),
        $now,
        $now
    ]);     
        $url = $app->url->create('users/list');
        $app->response->redirect($url);    

});


$app->router->add('list', function() use ($app) {
    $app->theme->setTitle("Visa alla");

        $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'list',
     
    ]);
 
});

$app->router->add('id', function() use ($app) { 

    $app->theme->setTitle("Visa en");
    $id = "1";
        $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'id',
        'params'     => [$id,],        
    ]);
 
});

$app->router->add('add', function() use ($app) {

    $app->theme->setTitle("Visa en");
    $id = "1";
        $app->dispatcher->forward([
        'controller' => 'users',
        'action'     => 'add',      
    ]);
 
});


$app->router->add('source', function() use ($app) {
    $app->theme->setVariable('wrapperclass', 'typography'); 
    $app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Källkod");
 
    $source = new \Mos\Source\CSource([
        'secure_dir' => '..', 
        'base_dir' => '..', 
        'add_ignore' => ['.htaccess'],
    ]);
 
    $app->views->add('me/source', [
        'content' => $source->View(),
    ]);
    

 
});
 
$app->router->handle();
$app->theme->render();
