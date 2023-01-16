<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';

include_once '../src/config/cfg.php';

// https://github.com/cnam/security-jwt-service-provider/

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\User;


$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__ . '/../views',
));

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
	'db.options' => array (
		'driver'    => 'pdo_mysql',
		'host'      => $cfg['db_host'],
		'dbname'    => $cfg['db_name'],
		'user'      => $cfg['db_user'],
		'password'  => $cfg['db_password'],
		'charset'   => 'utf8mb4',
	),
));
$prefix = $cfg['db_prefix'];

// if the app recieves a JSON request decode the content
$app->before(function (Request $request) {
	if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
		$data = json_decode($request->getContent(), true);
		$request->request->replace(is_array($data) ? $data : array());
	}
});

// allow cross origin requests
$app->after(function (Request $request, Response $response) {
	$response->headers->set('Access-Control-Allow-Origin', 'olmen.de');
	$response->headers->set('Access-Control-Allow-Headers', 'X-XSRF-TOKEN, Authorization, X-Access-Token, Content-Type');
	$response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PATCH, DELETE, OPTIONS');
});

// catch errors thrown in OlmApi::sendError()
$app->error(function(OlmServer\ApiException $error) use ($app) {
	$message = $error->getMessage();
	$code = $error->getCode();
	$response = $app->json(
		array(
			'status' => $code,
			'message' => $message
		),
		$code
	);
	$response->setStatusCode($code, $message);
	return $response;
});


$app['users'] = function() use ($app, $prefix) {
	return new OlmServer\UserManager($app['db'], $app['security.default_encoder'], $prefix);
};

$app['security.jwt'] = [
	'secret_key' => $cfg['jwt_secret'],
	'life_time'  => $cfg['jwt_lifetime'],
	'algorithm'  => ['HS256'],
	'options'    => [
		'header_name'  => 'X-Access-Token',
		'token_prefix' => 'Bearer',
	]
];

$app['security.firewalls'] = array(
	'login' => array(
		'pattern' => 'login|register|oauth|reset',
		'methods' => array('POST'),
		'anonymous' => true,
	),
	'maintenance' => array(
		'pattern' => 'setup|migrate|teapot',
		'methods' => array('GET'),
		'anonymous' => true,
	),
	'api-llp' => array(
		'pattern' => '^(\/api\/mcqs\/modules\/[0-9]+|\/api\/modules[^\/]|\/api\/generations|\/api\/texts/)',
		'methods' => array('GET'),
		'jwt' => array(
			'use_forward' => true,
			'require_previous_session' => false,
			'stateless' => true,
		),
		'anonymous' => true,
	),
	'secured' => array(
		'pattern' => '^\/api\/.+$',
		'methods' => array('GET', 'POST', 'PATCH', 'DELETE'),
		'logout' => array('logout_path' => '/logout'),
		'users' => $app['users'],
		'jwt' => array(
			'use_forward' => true,
			'require_previous_session' => false,
			'stateless' => true,
		)
	),
);

$app->register(new Silex\Provider\SecurityJWTServiceProvider());

$app['olmapi'] = function() use ($app, $prefix) {
	return new OlmServer\OlmApi($app['db'], $app, $prefix);
};

foreach($app['olmapi']->getRoutes() as $name => $route) {
	// add the routes defined by the server
	$app->{$route['method'] ?? 'post'}($route['route'], 'olmapi:' . $route['function'])->bind($name);
}

$app->options("{anything}", function (Application $app) {
	//return new \Symfony\Component\HttpFoundation\JsonResponse(null, 204);
	return $app->json(array('message' => 'Of course, that\'s ok!'), 200);
})->assert("anything", ".*");

$app->match("{start}", function (Application $app) {
	return $app['olmapi']->controllerInfo();
})->assert("start", "\/?([^\/]*|api|api\/)")->method('GET');

$app->match("{anything}", function (Application $app) {
	return $app['olmapi']->controllerNotDefined();
})->assert("anything", ".*");

//$app->match("{url}", function($url) use ($app) { return "OK"; })->assert('url', '.*')->method("OPTIONS");

$app->run();
