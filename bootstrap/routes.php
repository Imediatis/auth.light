<?php

use Digitalis\Core\Controllers\AuthApiController;
use Digitalis\Core\Middlewares\ApiTokenValidationMiddleware;
use Digitalis\Core\Middlewares\ClientFilterMiddleware;
use Slim\App;

//$app = new \Slim\App();

$c = $app->getContainer();

$app->group('', function (App $app) {
	$app->post('/operators/login', AuthApiController::class . ':logOperator')->setName('api.logoperator');
	$app->post('/users/login', AuthApiController::class . ':logUser')->setName('api.loguser');
})->add(new ApiTokenValidationMiddleware($c))->add(new ClientFilterMiddleware($c));