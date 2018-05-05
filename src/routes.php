<?php

use Jtn\TransmissionClient\Controllers\{TorrentsController, HomeController};

$app->get('/', HomeController::class . ':index');

$app->group('/api', function() {
    $this->get('/torrents', TorrentsController::class . ':index');
    $this->post('/torrents', TorrentsController::class . ':store');
    $this->patch('/torrents/{id}', TorrentsController::class . ':update');
});

