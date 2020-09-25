<?php

namespace Task\classes\actions;

class SuccessAction extends AbstractAction
{

    public function getName(): string
    {
        return 'Выполнено';
    }

    public function getPrivateName(): string
    {
        return 'done';
    }

    public function checkRule(int $user, int $executor, int $currentUser): bool
    {
        return $currentUser === $user;
    }
}
