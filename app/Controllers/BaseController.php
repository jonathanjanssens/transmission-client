<?php

namespace Jtn\TransmissionClient\Controllers;

use Slim\Container;

class BaseController
{

    /**
     * @var
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

}
