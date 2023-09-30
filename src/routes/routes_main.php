<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteCollectorProxy;
use OpenApi\Annotations as OA;
global $app;


/**
 * @OA\Info(
 *     title="TESTAPI",
 *     version="1.0",
 *     description="ALLES WAS ICH WISSEN MUSS",
 * )
 */



$app->get('/', function (Request $request, Response $response, $args) {

});

$app->group('/doc',function (RouteCollectorProxy $group){

    $group->get('/', function (Request $request, Response $response, $args) {
        global $app;
        /**
         * @OA\Get(
         *     path="/doc/",
         *     tags={"Documentation"},
         *     summary="Get the routes ",
         *     description="Returns a html of all Routes",
         *     @OA\Response(
         *         response=200,
         *         description="Greeting message",
         *         @OA\JsonContent(type="string")
         *     )
         * )
         */
        $swaggerUiHtml = file_get_contents(__DIR__ . '/../tpl/swagger/swagger.html');
        $swaggerjs = "<script>".file_get_contents(__DIR__.'/../../vendor/swagger-api/swagger-ui/dist/swagger-ui-bundle.js')."</script>";
        $swaggerjs .= "<script>".file_get_contents(__DIR__.'/../../vendor/swagger-api/swagger-ui/dist/swagger-ui-standalone-preset.js')."</script>";
        $swaggerjs .= "<script>".file_get_contents(__DIR__.'/../js/swagger-initializer.js')."</script>";
        $swaggerUiHtml = str_replace("{{swaggerjs}}",$swaggerjs,$swaggerUiHtml);
        $swaggercss = "<style>".file_get_contents(__DIR__.'/../../vendor/swagger-api/swagger-ui/dist/swagger-ui.css')."</style>";
        $swaggercss .= "<style>".file_get_contents(__DIR__.'/../../vendor/swagger-api/swagger-ui/dist/index.css')."</style>";
        $swaggerUiHtml = str_replace("{{swaggercss}}",$swaggercss,$swaggerUiHtml);
        $response->getBody()->write($swaggerUiHtml);;
        return $response->withHeader('Content-Type', 'text/html');
    });

    $group->get('/json', function (Request $request, Response $response, $args) {
        /**
         * @OA\Get(
         *     path="/doc/json",
         *     tags={"Documentation"},
         *     summary="Get the routes",
         *     description="Returns a JSON of all Routes",
         *     @OA\Response(
         *         response=200,
         *         description="Greeting message",
         *         @OA\JsonContent(type="string")
         *     )
         * )
         */
        $swagger = \OpenApi\Generator::scan([__DIR__ ]);
        $json =  $swagger->toJSON();
        $response->getBody()->write($json);
        return $response->withHeader('Content-Type', 'application/json');
    });
});

$app->group('/api',function (RouteCollectorProxy $group){
    $group->get('/hello/{name}', function (Request $request, Response $response, $args) {
        /**
         * @OA\Get(
         *     path="/api/hello/{name}",
         *     tags={"Api"},
         *     summary="Get a greeting message",
         *     description="Returns a greeting message based on the provided name.",
         *     @OA\Parameter(
         *         name="name",
         *         in="path",
         *         required=true,
         *         description="The name to include in the greeting.",
         *         @OA\Schema(type="string")
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Greeting message",
         *         @OA\JsonContent(type="string")
         *     )
         * )
         */
        $name = $args['name'];
        $response->getBody()->write("Hello, $name");
        return $response->withHeader('Content-Type', 'application/json');
    });
});