<?php
namespace PHPualizer;


use Slim\Http\Request;
use Slim\Http\Response;

class Routes
{
    private $app;
    
    public function __construct()
    {
        $this->app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);
    }
    
    public function startRouter()
    {
        $this->app->get('/', function(Request $req, Response $res) { Routes\Root::GET($req, $res); });
        $this->app->get('/account', function(Request $req, Response $res) { Routes\Account::GET($req, $res); });
        $this->app->get('/admin', function(Request $req, Response $res) { Routes\Admin::GET($req, $res); });
        $this->app->get('/modal/{name}', function(Request $req, Response $res) { Routes\Modals::GET($req, $res); });
        $this->app->get('/post/{name}', function(Request $req, Response $res) { $func = '\PHPualizer\Routes\Posts::' . $req->getAttribute('name'); $func($req, $res); });

        $this->app->run();
    }
    
    public function __destruct()
    {
        unset($this->app);
    }
}