<?php


namespace frexin\logic\actions;


class ResponseAction extends AbstractAction
{
    public static function getLabel()
    {
        return "Откликнуться";
    }

    public static function getInternalName()
    {
        return 'act_response';
    }

    public static function checkRights($userId, $performerId, $clientId)
    {
        return $userId !== $performerId;
    }
}
