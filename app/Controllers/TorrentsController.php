<?php

namespace Jtn\TransmissionClient\Controllers;

use Jtn\Transmission\Operations\AddTorrent;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Jtn\Transmission\Operations\{ListTorrents, ModifyTorrent};
use Slim\Http\UploadedFile;

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
        /** @var UploadedFile[] $files */
        $files = $request->getUploadedFiles();
        if(!isset($files['file'])) {
            // die
        }

        $op = new AddTorrent();
        $op->setFilename($files['file']->file);

        return $response->withJson($this->container->transmission->run($op)->body());
    }

}
