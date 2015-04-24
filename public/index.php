<?php
$loader = require_once __DIR__.'/../vendor/autoload.php';
$loader->add('Models', __DIR__ . '/../src');

$app = new Silex\Application();

$app['debug'] = true;

/** Register Twig **/
$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/../src/views',
));

/** Register Doctrine **/
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
  'db.options' => array(
    'driver'   => 'pdo_mysql',
    'dbname'   => 'sa_php',
    'host'     => 'localhost',
    'port'     => '8889',
    'user'     => 'root',
    'password' => 'root',
  ),
));

/** Register Form **/
use Symfony\Component\HttpFoundation\Request;

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks' => array('en'),
));

use Silex\Provider\FormServiceProvider;
$app->register(new FormServiceProvider());

/** Register Models **/
$app['model.posts'] = function ($app) {
  return new Models\PostsModel($app['db']);
};

$app['model.authors'] = function ($app) {
  return new Models\AuthorsModel($app['db']);
};

/** Register routes **/
$app->get('/', function () use ($app) {
  return $app['twig']->render('home.html', array(
    'posts' => $app['model.posts']->index(),
    'authors' => $app['model.authors']->index(),
  ));
});

$app->get('post/{id}', function ($id) use ($app) {
  return $app['twig']->render('post.html', array(
    'post' => $app['model.posts']->read($id),
  ));
});

$app->get('author/{id}', function ($id) use ($app) {
  return $app['twig']->render('author.html', array(
    'author' => $app['model.authors']->read($id),
    'posts' => $app['model.posts']->index($id),
  ));
});

$app->match('add/post', function (Request $request) use ($app) {
  $choices = array();
  
  foreach($app['model.authors']->index() as $author) {
    $choices[$author['id']] = $author['name'];
  }
  
  $data = array(
    'title' => '',
    'message' => '',
    'author' => '',
  );

  $form = $app['form.factory']->createBuilder('form', $data)
    ->add('title', 'text', array('label' => 'Title'))
    ->add('message', 'text', array('label' => 'Message'))
    ->add('author', 'choice', array('choices' => $choices, 'label' => 'Author'))
    ->add('save', 'submit', array('label' => 'Add Author'))
    ->getForm();

  $form->handleRequest($request);

  if ($form->isValid()) {
    $data = $form->getData();
    $app['model.posts']->create($data['title'], $data['message'], $data['author']);

    return $app->redirect('/');
  }
  
  return $app['twig']->render('add_post.html', array(
    'form' => $form->createView(),
  ));
});

$app->match('add/author', function (Request $request) use ($app) {
  $data = array(
    'name' => '',
  );

  $form = $app['form.factory']->createBuilder('form', $data)
    ->add('name', 'text', array('label' => 'Author\'s name'))
    ->add('save', 'submit', array('label' => 'Add Author'))
    ->getForm();

  $form->handleRequest($request);

  if ($form->isValid()) {
    $data = $form->getData();
    $app['model.authors']->create($data['name']);

    return $app->redirect('/');
  }
  
  return $app['twig']->render('add_author.html', array(
    'form' => $form->createView(),
  ));
});



$app->run();