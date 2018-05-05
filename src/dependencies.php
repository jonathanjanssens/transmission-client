<?php
// DIC configuration

$container = $app->getContainer();


$container['transmission'] = function(\Psr\Container\ContainerInterface $c) {
    $settings = $c->get('settings')['transmission'];
    return new Jtn\Transmission\Transmission(new \Jtn\Transmission\Config(
        $settings['host'], $settings['username'], $settings['password'], $settings['port']
    ));
};

// view renderer
$container['renderer'] = function (\Psr\Container\ContainerInterface $c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function (\Psr\Container\ContainerInterface $c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
