<?php
namespace frexin\logic;

use frexin\exceptions\StatusActionException;
use frexin\logic\actions\AbstractAction;
use frexin\logic\actions\CancelAction;
use frexin\logic\actions\CompleteAction;
use frexin\logic\actions\DenyAction;
use frexin\logic\actions\ResponseAction;

class AvailableActions
{
    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'proceed';
    const STATUS_CANCEL = 'cancel';
    const STATUS_COMPLETE = 'complete';
    const STATUS_EXPIRED = 'expired';

    const ROLE_PERFORMER = 'performer';
    const ROLE_CLIENT = 'customer';

    private $performerId = null;
    private $clientId = null;

    private $status = null;

    /**
     * AvailableActionsStrategy constructor.
     * @param string $status
     * @param int $performerId
     * @param int $clientId
     * @throws StatusActionException
     */
    public function __construct(string $status, ?int $performerId, int $clientId)
    {
        $this->setStatus($status);

        $this->performerId = $performerId;
        $this->clientId = $clientId;
    }

    public function getAvailableActions(string $role, int $userId):array
    {
        $this->checkRole($role);

        $statusActions = $this->statusAllowedActions()[$this->status];
        $roleActions = $this->roleAllowedActions()[$role];

        $allowedActions = array_intersect($statusActions, $roleActions);

        $allowedActions = array_filter($allowedActions, function ($action) use ($userId) {
            return $action::checkRights($userId, $this->performerId, $this->clientId);
        });

        return array_values($allowedActions);
    }

    public function getNextStatus(AbstractAction $action):?string
    {
        $map = [
            CompleteAction::class => self::STATUS_COMPLETE,
            CancelAction::class => self::STATUS_CANCEL,
            DenyAction::class => self::STATUS_COMPLETE,
            ResponseAction::class => null,
            AbstractAction::class => null
        ];

        return $map[get_class($action)];
    }

    public function setStatus(string $status):void
    {
        $availableStatuses = [self::STATUS_NEW, self::STATUS_IN_PROGRESS, self::STATUS_CANCEL, self::STATUS_COMPLETE,
            self::STATUS_EXPIRED];

        if (!in_array($status, $availableStatuses)) {
            throw new StatusActionException("Неизвестный статус: $status");
        }

        $this->status = $status;
    }

    public function checkRole(string $role):void
    {
        $availableRoles = [self::ROLE_PERFORMER, self::ROLE_CLIENT];

        if (!in_array($role, $availableRoles)) {
            throw new StatusActionException("Неизвестная роль: $role");
        }
    }

    /**
     * Возвращает действия, доступные для каждой роли
     * @return array
     */
    private function roleAllowedActions():array
    {
        $map = [
            self::ROLE_CLIENT => [CancelAction::class, CompleteAction::class],
            self::ROLE_PERFORMER => [ResponseAction::class, DenyAction::class]
        ];

        return $map;
    }

    /**
     * Возвращает действия, доступные для каждого статуса
     * @return array
     */
    private function statusAllowedActions():array {
        $map = [
            self::STATUS_CANCEL => [],
            self::STATUS_COMPLETE => [],
            self::STATUS_IN_PROGRESS => [DenyAction::class, CompleteAction::class],
            self::STATUS_NEW => [CancelAction::class, ResponseAction::class],
            self::STATUS_EXPIRED => []
        ];

        return $map;
    }

    private function getStatusMap():array
    {
        $map = [
            self::STATUS_NEW => [self::STATUS_EXPIRED, self::STATUS_CANCEL],
            self::STATUS_IN_PROGRESS => [self::STATUS_CANCEL, self::STATUS_COMPLETE],
            self::STATUS_CANCEL => [],
            self::STATUS_COMPLETE => [],
            self::STATUS_EXPIRED => [self::STATUS_CANCEL]
        ];

        return $map;
    }

}