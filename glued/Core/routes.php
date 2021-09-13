<?php
use Geocoder\geocode;
use Glued\Core\Controllers\Glued;
use Glued\Core\Classes\Utils\Utils;
use Glued\Core\Controllers\AuthController;
use Glued\Core\Controllers\AdmController;
use Glued\Core\Controllers\ProxyController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;


// Homepage
$app->get('/', Glued::class)->setName('app.core.root');

$app->group('/core', function (RouteCollectorProxy $route) {
    $route->get ('/auth/callback', Glued::class . ':signin')->setName('app.core.auth.callback');
    $route->get ('/auth/signout', AuthController::class . ':keycloak_signout')->setName('app.core.auth.signout');
    $route->get ('/auth/confidential/whoami', AuthController::class . ':keycloak_whoami')->setName('app.core.auth.confidential.whoami');
    $route->get ('/auth/confidential/adm', AuthController::class . ':keycloak_adm')->setName('app.core.auth.confidential.adm');
    $route->get ('/auth/enforce', AuthController::class . ':enforcer')->setName('app.core.auth.enforce');
    $route->get ('/phpinfo', function (Request $request, Response $response) {
        phpinfo();
        return $response;
    })->setName('app.core.phpinfo');
    $route->get ('/phpconst', function(Request $request, Response $response) { 
        highlight_string("<?php\nget_defined_constants() =\n" . var_export(get_defined_constants(true), true) . ";\n?>");
        return $response; 
    })->setName('app.core.phpconst');
});

$app->group('/api/core', function (RouteCollectorProxy $route) {
    $route->get ('/routes/v1', AdmController::class . ':routes')->setName('api.core.routes.v1');
    $route->get ('/healtcheck/v1/fe', ProxyController::class . ':fe_healthcheck')->setName('api.core.adm.healtcheck.fe.v1');
    $route->get ('/healtcheck/v1/be', ProxyController::class . ':be_healthcheck')->setName('api.core.adm.healtcheck.be.v1');
});


