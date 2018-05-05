<?php

namespace Jtn\TransmissionClient\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use Jtn\Transmission\Operations\{ListTorrents, ModifyTorrent};

class TorrentsController extends BaseController
{

    public function index(Request $request, Response $response)
    {
        $op = new ListTorrents;
        $op->setFields('all');
        return $response->withJson($this->container->transmission->run($op)->body());
    }

    public function update(Request $request, Response $response, array $args)
    {
        $action = $request->getParsedBody()['action'];
        $id = $args['id'];

        $op = new ModifyTorrent;

        if(is_numeric($id)) {
            $id = (int) $id;
            $op->setIds([$id]);
        }

        if(in_array($action, ['stop', 'pause']))
            $op->pause();
        else
            $op->start();

        return $response->withJson($this->container->transmission->run($op)->body());
    }

    public function store(Request $request, Response $response)
    {

    }

}
