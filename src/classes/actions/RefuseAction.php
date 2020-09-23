<?php

namespace Task\classes\actions;

class RefuseAction extends AbstractAction
{

    public function getName(): string
    {
        return 'Отказаться';
    }

    public function getPrivateName(): string
    {
        return 'deny';
    }

    public function checkRule(int $user, int $executor, int $currentUser): bool
    {
        return $currentUser === $executor;
    }
}
