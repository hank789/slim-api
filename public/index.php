<?php

require __DIR__ . '/../vendor/autoload.php';

use Ylc\AppBackendApi\ApiConfig;
use Ylc\AppBackendApi\Validation\Translator;
use Ylc\AppBackendApi\Validation\Factory as ValidatorFactory;
use Ylc\AppBackendApi\Controllers\Controller;
use Ylc\AppBackendApi\Component\JsonApiMiddleware;

define('APP_START', microtime(true));

define('ROOT_PATH', __DIR__ . '/../');
define('APP_PATH', ROOT_PATH . '/app');

$config = ApiConfig::AppSlimConfig();

//初始化验证类工厂对象
$validator = new ValidatorFactory(new Translator);

$app = new \Slim\Slim($config);

$app->validator = $validator;
$app->config    = $config;
$app->add(new JsonApiMiddleware());

Controller::$app = $app;

$app->get('/', 'Ylc\AppBackendApi\Controllers\HomeController:index');
$app->get('/test', 'Ylc\AppBackendApi\Controllers\HomeController:jsonpDemo');
$app->post('/dologin','Ylc\AppBackendApi\Controllers\UserController:doLogin');

$app->run();
