<?php 
/**
 * This is a Anax pagecontroller.
 *
 */

// Get environment & autoloader and the $app-object.
require __DIR__.'/config_with_app.php';
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->navbar->configure(ANAX_APP_PATH . 'config/navbar_theme.php');



// Set the title of the page
$app->theme->setVariable('title', "Tema");
 
$app->router->add('', function() use ($app) {
     $app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
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
     $app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
     $app->theme->setTitle("Regioner");  
     $app->theme->setVariable('wrapperclass', 'regioner');        
//     $content = $app->fileContent->get('tema.md');   
//     $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
     
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
     $app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
     $app->theme->setTitle("Rutnät");  
     $app->theme->setVariable('wrapperclass', 'rutor');        
     $content = $app->fileContent->get('rutor.md');   
     $content = $app->textFilter->doFilter($content, 'shortcode, markdown');
     $app->views->add('theme/page', [        
        'content' => $content,
    ]);     

 
});

$app->router->add('typography', function() use ($app) {
     $app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
     $app->theme->setVariable('wrapperclass', 'typografi');    
     $app->theme->setTitle("Typografi");  
     $content = $app->fileContent->get('typography.html');   

     $app->views->add('theme/page', [        
        'content' => $content,
    ]);     
    $app->views->addString($content, 'sidebar');
 
});

$app->router->add('fontawesome', function() use ($app) {
     $app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
     $app->theme->setTitle("Font Awesome");  
     $app->theme->setVariable('wrapperclass', 'typsnitt');
     $content = $app->fileContent->get('fontawesome.html');   
     $sidebar = $app->fileContent->get('variationer.html');   

     $app->views->add('theme/font', [        
        'content' => $content,
    ]);     
    $app->views->addString($sidebar, 'sidebar');
 
});

$app->router->handle();
$app->theme->render();
