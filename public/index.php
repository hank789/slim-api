<?php
use Ylc\EnvConfig\Environment;
use Ylc\AppBackendApi\ApiConfig;
use Ylc\AppBackendApi\Resource;
use Slim\Slim;

require('../vendor/autoload.php');

$app = new Slim(ApiConfig::AppSlimConfig());

if (Environment::current()->notProduction()) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}
date_default_timezone_set('Asia/Shanghai');

// Get
$app->get('/user(/(:id)(/))', function($id = null) {
    $resource = Resource::load('user');
    if ($resource === null) {
        Resource::response(Resource::STATUS_NOT_FOUND);
    } else {
        $resource->get($id);
    }
});

$app->run();