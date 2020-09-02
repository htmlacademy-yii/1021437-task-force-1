<?php

namespace Task\classes;

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
        self::STATUS_CANCEL => 'отменено',
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

    private $id_performer;
    private $id_client;
    private $role;

    const MAP_STATUSES_AND_ACTIONS = [
        0 => [
            self::STATUS_NEW => self::ACTION_CANCEL,
            self::STATUS_IN_WORK => self::ACTION_SUCCESS
        ],
        1 => [
            self::STATUS_NEW => self::ACTION_RESPOND,
            self::STATUS_IN_WORK => self::ACTION_REFUSE
        ]
    ];

    public function __construct($id_client, $id_performer, $role)
    {
        $this->id_client = $id_client;
        $this->id_performer = $id_performer;
        $this->role = $role;
    }

    public function getMapStatuses()
    {
        return self::MAP_STATUSES_NAME;
    }

    public function getMapActions()
    {
        return self::MAP_ACTIONS_NAME;
    }

    public function getNextStatus($action)
    {
        return isset(self::MAP_STATUSES[$action]) ? self::MAP_STATUSES[$action] : '';
    }

    public function getActionsFromStatus($status)
    {
        return isset(self::MAP_STATUSES_AND_ACTIONS[$this->role][$status]) ?
            self::MAP_STATUSES_AND_ACTIONS[$this->role][$status] : '';
    }
}
