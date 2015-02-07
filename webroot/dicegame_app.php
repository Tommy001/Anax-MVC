<?php 
/**
 * This is a Anax frontcontroller.
 *
 */

// Get environment & autoloader.
require __DIR__.'/config_with_app.php'; 



// Add extra assets
$app->theme->addStylesheet('css/dicegame.css');


// Home route
$app->router->add('', function() use ($app) {

    $app->views->add('me/dicegame');
    $app->theme->setTitle("Tärningsspel");

});


// Route to show welcome to dice
$app->router->add('dicegame', function() use ($app) {

    $app->views->add('me/dicegame');
    $app->theme->setTitle("Kasta tärning");

});

// Route to roll dice and show results
$app->router->add('dicegame/round', function() use ($app) {

    // Kolla om spelaren rullar tärningen
    $roll = $app->request->getGet('roll', false);
    $save = $app->request->getGet('save', false);
    $init = $app->request->getGet('init', false);    
    if($init) {
        unset($_SESSION['play100']);
    }

    if(isset($_SESSION['play100'])) {
        $play100 = $_SESSION['play100'];
    } else {
        $play100 = new CDiceGameRound();
        $_SESSION['play100'] = $play100;
    }

    $html = $play100->GetGameBoard(); // visa spelplanen

    if ($roll && !$play100->Reach100()) { // tillåt kast om poängen < 100
        $html = $play100->IfRollDice();
    }

    if ($save) { // efter klick på Spara: 
        $html = $play100->SaveRound($round); // spara och få tillbaka spelplanen
    }


    $app->views->add('me/dicegame', [
        'html'      => $html,
    ]);

});


// Check for matching routes and dispatch to controller/handler of route
$app->router->handle();

// Render the page
$app->theme->render();
