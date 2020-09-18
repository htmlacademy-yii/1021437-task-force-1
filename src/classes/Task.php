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

    private $executor_id;
    private $client_id;
    private $current_user;
    private $action;

    const MAP_STATUSES_AND_ACTIONS = [
        self::STATUS_NEW => [self::ACTION_CANCEL, self::ACTION_RESPOND],
        self::STATUS_IN_WORK => [self::ACTION_SUCCESS, self::ACTION_REFUSE]
    ];

    public function __construct($client_id, $executor_id, $current_user)
    {
        $this->client_id = $client_id;
        $this->executor_id = $executor_id;
        $this->current_user = $current_user;

        $this->action['cancel'] = new CancelAction();
        $this->action['deny'] = new RefuseAction();
        $this->action['respond'] = new RespondAction();
        $this->action['done'] = new SuccessAction();
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
        foreach (self::MAP_STATUSES_AND_ACTIONS[$status] as $action) {
            if ($this->action[$action]->checkRule($this->client_id, $this->executor_id, $this->current_user)) {
                return $this->action[$action];
            }
        }
        return null;
    }
}
