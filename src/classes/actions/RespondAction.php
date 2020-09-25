<?php

namespace Task\classes\actions;

class RespondAction extends AbstractAction
{

    public function getName(): string
    {
        return 'Откликнуться';
    }

    public function getPrivateName(): string
    {
        return 'respond';
    }

    public function checkRule(int $user, int $executor, int $currentUser): bool
    {
        return $executor === $currentUser;
    }
}
