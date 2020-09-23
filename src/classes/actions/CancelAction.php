<?php

namespace Task\classes\actions;

class CancelAction extends AbstractAction
{

    public function getName(): string
    {
        return 'Отменить';
    }

    public function getPrivateName(): string
    {
        return 'cancel';
    }

    public function checkRule(int $user, int $executor, int $currentUser): bool
    {
        return $user === $currentUser;
    }
}
