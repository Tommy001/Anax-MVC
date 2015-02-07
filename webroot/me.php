<?php 
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php';

$app->theme->configure(ANAX_APP_PATH . 'config/theme_me.php');
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_me.php');
$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');

$baseurl = realpath(__DIR__ . '/../../') . '/';


// Set the title of the page
$app->theme->setVariable('title', "Min Me-sida");
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
 
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
     
});
 
$app->router->add('tema', function() use ($app) {
 
    $app->theme->setTitle("Tema");
    $content = $app->fileContent->get('tema.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
 
 
    $app->views->add('me/theme', [
        'content' => $content,
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
