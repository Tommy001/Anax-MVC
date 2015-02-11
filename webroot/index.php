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
    $controller = new Phpmvc\Comment\CommentController();
    $controller->setDI($di);
    return $controller;
});



// Set the title of the page
$app->theme->setVariable('title', "Min Me-sida");

 
$app->router->add('', function() use ($app) {

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
 
$app->router->add('source', function() use ($app) {
 
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
