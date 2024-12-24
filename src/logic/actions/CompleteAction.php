<?php


namespace frexin\logic\actions;


class CompleteAction extends AbstractAction
{
    public static function getLabel()
    {
        return "Завершить";
    }

    public static function getInternalName()
    {
        return "act_complete";
    }

    public static function checkRights($userId, $performerId, $clientId)
    {
        return $clientId == $userId;
    }

}