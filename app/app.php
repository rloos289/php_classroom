<?php
    date_default_timezone_set('America/Los_Angeles');
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/template.php";

    //Epicodus
    $server = 'mysql:host=localhost;dbname=best_restaurants_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    //home mac
    // $server = 'mysql:host=localhost:8889;dbname=best_restaurants';
    // $username = 'root';
    // $password = 'root';
    // $DB = new PDO($server, $username, $password);

    // session_start();
    // if (empty($_SESSION['collection'])) {
    //     $_SESSION['collection'] = array();
    // }

    $app = new Silex\Application();

    $app['debug'] = true;

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

  //loads actual twig file
    $app->get("/", function() use ($app) {
      return $app['twig']->render("home.html.twig");
    });

  //loads basic php
    $app->get("/test", function() use ($app) {
      return 'test variables here';
    });

    return $app;
?>
