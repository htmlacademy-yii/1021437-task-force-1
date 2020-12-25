<?php
declare(strict_types=1);

namespace Task\classes;

use Task\classes\actions\AbstractAction;
use Task\classes\actions\CancelAction;
use Task\classes\actions\RefuseAction;
use Task\classes\actions\RespondAction;
use Task\classes\actions\SuccessAction;
use Task\classes\exceptions\IncorrectRoleException;
use Task\classes\exceptions\IncorrectStatusException;
use Task\classes\exceptions\IncorrectActionException;

class Task
{
    const STATUS_NEW = 'new';
    const STATUS_CANCEL = 'canceled';
    const STATUS_IN_WORK = 'in_work';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    const ACTION_CANCEL = 'cancel';
    const ACTION_REFUSE = 'deny';
    const ACTION_RESPOND = 'respond';
    const ACTION_SUCCESS = 'done';

    const MAP_STATUSES_NAME = [
        self::STATUS_NEW => 'новое',
        self::STATUS_CANCEL => 'отменена',
        self::STATUS_IN_WORK => 'в работе',
        self::STATUS_SUCCESS => 'выполнено',
        self::STATUS_FAILED => 'провалено',
    ];

    const MAP_ACTIONS_NAME = [
        self::ACTION_CANCEL => 'отменить',
        self::ACTION_SUCCESS => 'выполнено',
        self::ACTION_REFUSE => 'отказаться',
        self::ACTION_RESPOND => 'откликнуться',
    ];

    const MAP_STATUSES = [
        self::ACTION_CANCEL => self::STATUS_CANCEL,
        self::ACTION_SUCCESS => self::STATUS_SUCCESS,
        self::ACTION_REFUSE => self::STATUS_FAILED,
        self::ACTION_RESPOND => self::STATUS_IN_WORK,
    ];

    private $executorId;
    private $clientId;
    private $currentUser;
    public $actions = [];
    private $status;

    const MAP_STATUSES_AND_ACTIONS = [
        self::STATUS_NEW => [self::ACTION_CANCEL, self::ACTION_RESPOND],
        self::STATUS_IN_WORK => [self::ACTION_SUCCESS, self::ACTION_REFUSE]
    ];

    public function __construct(int $clientId, int $executorId, int $currentUser, string $status)
    {
        $this->clientId = $clientId;
        $this->executorId = $executorId;
        $this->currentUser = $currentUser;

        $this->actions = [
            'cancel' => new CancelAction(),
            'deny' =>new RefuseAction(),
            'respond' => new RespondAction(),
            'done' => new SuccessAction()
        ];

        if (!array_key_exists($status, self::MAP_STATUSES_NAME)) {
            throw new IncorrectStatusException('Не правильный указан статус');
        }

        $this->status = $status;
    }

    public function getMapStatuses(): array
    {
        return self::MAP_STATUSES_NAME;
    }

    public function getMapActions(): array
    {
        return self::MAP_ACTIONS_NAME;
    }

    public function getNextStatus(string $action): string
    {
        if (!isset(self::MAP_STATUSES[$action])) {
            throw new IncorrectActionException('Такого действия не существует');
        }
        return isset(self::MAP_STATUSES[$action]) ? self::MAP_STATUSES[$action] : '';
    }

    public function getActionsFromStatus(): ?AbstractAction
    {
        if ($this->currentUser !== $this->clientId && $this->executorId !== $this->currentUser) {
            throw new IncorrectRoleException('Нет такой роли у пользователя');
        }
        if (!self::MAP_STATUSES_AND_ACTIONS[$this->status]) {
            throw new IncorrectRoleException('Задача имеет конечный статус');
        }
        foreach (self::MAP_STATUSES_AND_ACTIONS[$this->status] as $action) {
            if ($this->actions[$action]->checkRule($this->clientId, $this->executorId, $this->currentUser)) {
                return $this->actions[$action];
            }
        }

        return null;
    }
}
