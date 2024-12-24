<?php
namespace frexin\logic\actions;

abstract class AbstractAction
{
    abstract public static function getLabel();

    abstract public static function getInternalName();

    abstract public static function checkRights($userId, $performerId, $clientId);
}
