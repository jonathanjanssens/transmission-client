<?php

namespace Jtn\TransmissionClient\Controllers;
use Slim\Http\Request;
use Slim\Http\Response;

class HomeController extends BaseController
{

    public function index(Request $request, Response $response)
    {
        return $this->container->renderer->render($response, 'index.phtml');
    }

}
