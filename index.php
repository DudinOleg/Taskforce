<?php
require_once 'vendor/autoload.php';
ini_set('assert.exception', 1);

use frexin\exceptions\StatusActionException;
use frexin\logic\actions\ResponseAction;
use frexin\logic\AvailableActions;

try {
    $strategy = new AvailableActions(AvailableActions::STATUS_NEW, 3, 1);

    $nextStatus = $strategy->getNextStatus(new ResponseAction());
} catch (StatusActionException $e) {
    die($e->getMessage());
}

var_dump('new -> performer', $strategy->getAvailableActions(AvailableActions::ROLE_PERFORMER, 2));
var_dump('new -> client,alien', $strategy->getAvailableActions(AvailableActions::ROLE_CLIENT, 2));
var_dump('new -> client,same', $strategy->getAvailableActions(AvailableActions::ROLE_CLIENT, 1));

var_dump('proceed -> performer,same', $strategy->getAvailableActions(AvailableActions::ROLE_PERFORMER, 3));

